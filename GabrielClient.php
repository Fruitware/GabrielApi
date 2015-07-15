<?php

namespace Fruitware\GabrielApi;

use Fruitware\GabrielApi\Exception\BadResponseException;
use Fruitware\GabrielApi\Model\CustomerInterface;
use Fruitware\GabrielApi\Model\PassengerInterface;
use Fruitware\GabrielApi\Model\PaymentInterface;
use Fruitware\GabrielApi\Model\SearchInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Command\Guzzle\DescriptionInterface;
use GuzzleHttp\Event\BeforeEvent;
use Psr\Log\LoggerInterface;

/**
 * @method array getSupportedLanguages()
 * @method array getBestPrice()
 * @method array getCalendarShopping(array $args)
 * @method array getCurrencyExchange(array $args)
 * @method array getDefaultSearchSettings()
 * @method array getSegmentsSearchHistory(array $args)
 * @method array clearSegments(array $args)
 * @method array getTotalCost()
 * @method array setPassengerAsCustomer(array $args)
 * @method array getCurrentBooking()
 * @method array finalizeBooking() - confirm
 */
class GabrielClient extends \GuzzleHttp\Command\Guzzle\GuzzleClient
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $defaultLang = SearchInterface::LANG_RO;

    /**
     * @param ClientInterface      $client
     * @param SessionInterface     $session
     * @param DescriptionInterface $description
     * @param array                $config
     */
    public function __construct(ClientInterface $client = null, SessionInterface $session = null, DescriptionInterface $description = null, array $config = [])
    {
        $this->session = $session instanceof SessionInterface  ? $session : new Session();
        $client = $client instanceof ClientInterface ? $client : new Client();
        $description = $description instanceof DescriptionInterface ? $description : new Description();

        parent::__construct($client, $description, $config);

        $this->getHttpClient()->setDefaultOption('headers', [
            'Content-Type' => 'application/json; charset=utf-8'
        ]);

        $this->getHttpClient()->getEmitter()->on('before', function (BeforeEvent $event) {
            if ($event->getRequest()->getPath() !== '/GabrielAPI/Account/GetTicket') {
                if (!$this->getSession()->getToken()) {
                    $this->login();
                }

                if ($this->getSession()->getToken()) {
                    $event
                        ->getRequest()
                        ->getQuery()
                        ->add('session_id', $this->getSession()->getToken());
                } else {
                    throw new BadResponseException('Request require session token');
                }
            }
        });
    }

    /**
     * Set cache
     *
     * @param CacheInterface $cache
     */
    public function setCache(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Set logger (monolog required)
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        $this->logger->pushProcessor(function (array $record) {
            $record['extra']['token'] = $this->getSession()->getToken();

            return $record;
        });
    }

    /**
     * Get session object
     *
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Authenticate and obtain a work session in B2B Portal system, if token doesn't exist
     *
     * @param bool $retry Retry login if some error
     *
     * @throws \Exception
     * @return string Session token
     */
    public function login($retry = true)
    {

        if (!$this->getSession()->getToken()) {
            try {
                $result = parent::login([
                    'login'    => $this->getSession()->getLogin(),
                    'password' => $this->getSession()->getPassword()
                ]);
                $token = $result['session_id'];
                $this->getSession()->setToken($token);
                if ($this->logger) $this->logger->debug('The new session created');
            }
            catch (\Exception $ex) {
                if ($retry) {
                    if ($this->logger) $this->logger->info(__METHOD__.' Login failed');
                    $this->login(false);
                }

                if ($this->logger) $this->logger->warning(__METHOD__.' Login failed');
                throw $ex;
            }

            $this->_setCache('lang', $this->defaultLang, 10);
        }

        return $this->getSession()->getToken();
    }

    /**
     * Start new booking session (Session Flush) - resets all data previously recorded in the number of session
     *
     * @return string New session token
     */
    public function reset()
    {
        $result = parent::reset();
        $newToken = $result['session_id'];

        $this->_deleteCache('lang');
        $this->_deleteCache('numberOfPassengers');
        $this->_deleteCache('getSegmentsArgs');
        $this->_deleteCache('getSegments');
        $this->_deleteCache('setSegments');
        $this->_deleteCache('setCustomer');
        $this->_deleteCache('setPassengers');
        $this->_deleteCache('setPayment');

        $this->getSession()->setToken($newToken);

        return $this->getSession()->getToken();
    }

    /**
     * Get all cities or find by partial name or ISO code of searching city
     *
     * @param string $city Partial name or ISO code of searching city or empty for get all cities
     *
     * @return array
     */
    public function getCityName($city = null)
    {
        if (is_null($city)) $city = '';
        $cacheKey = 'city_'.$city;
        $result = $this->_getCache($cacheKey, false);
        if (!$result) {
            $args = ['city' => $city];
            $result = parent::getCityName($args);
            $this->_setCache($cacheKey, $result, 60);
        }

        return $result;
    }

    /**
     * Get all airlines or find by partial name or IATA code of searching airline
     *
     * @param string $airline Partial name or IATA code of searching airline or empty for get all airlines
     *
     * @return array
     */
    public function getAirlineName($airline = null)
    {
        if (is_null($airline)) $airline = '';
        $cacheKey = 'airline_'.$airline;
        $result = $this->_getCache($cacheKey, false);
        if (!$result) {
            $args = ['airline' => $airline];
            $result = parent::getAirlineName($args);
            $this->_setCache($cacheKey, $result, 60);
        }

        return $result;
    }

    /**
     * Get all countries or find by partial name or ISO code of searching country
     *
     * @param string $country Partial name or ISO code of searching country or empty for get all countries
     *
     * @return array
     */
    public function getCountryName($country = null)
    {
        if (is_null($country)) $country = '';
        $cacheKey = 'country_'.$country;
        $result = $this->_getCache($cacheKey, false);
        if (!$result) {
            $args = ['country' => $country];
            $result = parent::getCountryName($args);
            $this->_setCache($cacheKey, $result, 60);
        }

        return $result;
    }

    /**
     * Search segments
     *
     * @param SearchInterface $search
     *
     * @return array of segments
     */
    public function search(SearchInterface $search)
    {
        if ($this->logger) $this->logger->debug(__METHOD__.' called');

        $this->changeLanguage($search->getLang());

        $this->setNumberOfPassengers($search->getAdults(), $search->getChildren(), $search->getInfants());

        $getSegments = $this->getSegments(
            $search->getDepartureAirport(),
            $search->getArrivalAirport(),
            $search->getDepartureDate(),
            $search->getReturnDate(),
            $search->getSearchOption()
        );

        return $getSegments;
    }

    /**
     * @param string $lang
     */
    public function changeLanguage($lang)
    {
        if ($lang !== $this->_getCache('lang')) {
            $args = [
                'culture_code' => $lang
            ];
            parent::changeLanguage($args);
            $this->_setCache('lang', $lang, 10);
        }
        else {
            if ($this->logger) $this->logger->debug(__METHOD__.' -> used from the cache');
        }
    }

    /**
     * @param int $adults
     * @param int $children
     * @param int $infants
     */
    public function setNumberOfPassengers($adults, $children, $infants)
    {
        $passengers = [
            'seats'    => (int)$adults + (int)$children,
            'children' => (int)$children,
            'infants'  => (int)$infants
        ];

        if ($passengers !== $this->_getCache('numberOfPassengers')) {
            parent::setNumberOfPassengers($passengers);
            $this->_setCache('numberOfPassengers', $passengers, 10);
            $this->_deleteCache('getSegments');
        }
        else {
            if ($this->logger) $this->logger->debug(__METHOD__.' -> used from the cache');
        }
    }

    /**
     * @param           $departureAirport
     * @param           $arrivalAirport
     * @param \DateTime $departureDate
     * @param \DateTime $returnDate
     * @param           $searchOption
     *
     * @return array
     */
    public function getSegments($departureAirport, $arrivalAirport, \DateTime $departureDate, \DateTime $returnDate = null, $searchOption = 1)
    {
        $newGetSegmentsArgs = [
            'airport_from'  => $departureAirport,
            'airport_to'    => $arrivalAirport,
            'dep_date'      => $departureDate->format('Y-m-d'),
            'ret_date'      => $returnDate ? $returnDate->format('Y-m-d') : null,
            'search_option' => $searchOption
        ];

        $getSegmentsArgs = $this->_getCache('getSegmentsArgs');
        if ($newGetSegmentsArgs !== $getSegmentsArgs || !$this->_getCache('getSegments')) {
            $getSegments = parent::getSegments($newGetSegmentsArgs);
            $this->_setCache('getSegmentsArgs', $newGetSegmentsArgs, 5);
            $this->_setCache('getSegments', $getSegments, 5);
        }
        else {
            $getSegments = $this->_getCache('getSegments');
            if ($this->logger) $this->logger->debug(__METHOD__.' used from the cache');
        }

        return $getSegments;
    }

    /**
     * @param int      $optionId     Selected price option
     * @param null|int $optionIdBack Selected price option back if is roundtrip search
     * @param int      $searchOption Reference to searching identifier search_option
     */
    public function setSegment($optionId, $optionIdBack = null, $searchOption = 1)
    {
        $setSegments = $this->_getCache('setSegments');

        $segments = [
            'option_id' => $optionId,
            'option_id_back' => $optionIdBack,
            'search_option' => $searchOption,
        ];

        if ($setSegments !== $segments) {
            parent::setSegment($segments);
            $this->_setCache('setSegments', $segments, 10);
        }
        else {
            if ($this->logger) $this->logger->debug(__METHOD__.' used from the cache');
        }
    }

    /**
     * @param \Iterator $passengersIterator
     */
    public function setPassengers(\Iterator $passengersIterator)
    {
        $setNewPassengers = [];
        /** @var PassengerInterface $passenger */
        foreach ($passengersIterator as $passenger) {
            if (!$passenger instanceof PassengerInterface) {
                throw new \InvalidArgumentException('passenger must me instance of PassengerInterface');
            }

            $setNewPassengers[] = $passenger->toArray();
        }

        $setPassengers = $this->_getCache('setPassengers');

        if ($setPassengers !== $setNewPassengers) {
            parent::setPassengers([
                'passengers' => $setNewPassengers
            ]);
            $this->_setCache('setPassengers', $setNewPassengers, 10);
        }
        else {
            if ($this->logger) $this->logger->debug(__METHOD__.' used from the cache');
        }
    }

    /**
     * @param CustomerInterface $customer
     */
    public function setCustomer(CustomerInterface $customer)
    {
        if ($customer instanceof PassengerInterface) {
            $setNewCustomer = $customer->toCustomerArray();
        }
        else {
            $setNewCustomer = $customer->toArray();
        }

        $setCustomer = $this->_getCache('setCustomer');

        if ($setCustomer !== $setNewCustomer) {
            if ($customer instanceof PassengerInterface) {
                $this->setPassengerAsCustomer($setNewCustomer);
            }
            else {
                parent::setCustomer($setNewCustomer);
            }
            $this->_setCache('setCustomer', $setNewCustomer, 10);
        }
        else {
            if ($this->logger) $this->logger->debug(__METHOD__.' used from the cache');
        }
    }

    /**
     * Set payment type: CA – cash, CC – credit card, IN – invoice
     *
     * @param string $type
     */
    public function setFormOfPayment($type)
    {
        if (!in_array($type, [PaymentInterface::TYPE_CASH, PaymentInterface::TYPE_CREDIT_CARD, PaymentInterface::TYPE_INVOICE])) {
            throw new \InvalidArgumentException('type '.$type.' is not valid');
        }

        $setPayment = $this->_getCache('setPayment');

        if ($setPayment !== $type) {
            parent::setFormOfPayment([
                'form_of_payment' => $type
            ]);
            $this->_setCache('setPayment', $type, 10);
        }
        else {
            if ($this->logger) $this->logger->debug(__METHOD__.' used from the cache');
        }
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @throws \Exception
     * @return array
     */
    public function __call($name, array $arguments)
    {
        if ($this->logger) $this->logger->debug(__METHOD__.' -> '.$name.' request', ['data' => $arguments]);

        try {
            $response = parent::__call($name, $arguments);

            if ($this->checkSuccessResponse($response)) {
                if ($this->logger) $this->logger->info(__METHOD__.' -> '.$name.' result', ['data' => $response['result']]);
                return $response['result'];
            }
        }
        catch (\Exception $ex) {
            if ($this->logger) $this->logger->critical(__METHOD__.' -> '.$name.' result error', ['data' => $ex->getMessage(), 'file' => $ex->getFile(), 'code' => $ex->getCode(), 'line' => $ex->getLine(), 'trace' => $ex->getTraceAsString()]);

            throw $ex;
        }

        if ($this->logger) $this->logger->critical(__METHOD__.' -> '.$name.' result error', ['message' => $response['message'], 'code' => $response['code']]);

        throw new BadResponseException($response['message'], $response['code']);
    }

    /**
     * @param array $response
     *
     * @return bool
     */
    protected function checkSuccessResponse(array $response)
    {
        if (isset($response['code']) && 0 === $response['code'] && 'Success' === $response['message']) {
            return true;
        }

        return false;
    }


    /**
     * @param string $key
     * @param mixed  $value
     * @param int    $minutes
     * @param bool   $sessionTokenPrefix
     *
     * @return mixed|null
     */
    protected function _setCache($key, $value, $minutes, $sessionTokenPrefix = true)
    {
        if ($this->logger) $this->logger->debug(__METHOD__.' called', ['key' => $key, 'value' => $value, 'minutes' => $minutes, 'sessionTokenPrefix' => $sessionTokenPrefix]);

        if ($this->cache) {
            if ($sessionTokenPrefix) {
                $key = $this->getSession()->getToken().'_'.$key;
            }

            if ($this->logger) $this->logger->debug(__METHOD__.' set', ['key' => $key, 'value' => $value, 'minutes' => $minutes]);
            $result = $this->cache->set($key, $value, $minutes);
            if ($this->logger) $this->logger->debug(__METHOD__.' result', ['data' => $result]);

            return $result;
        }

        if ($this->logger) $this->logger->debug(__METHOD__.' cache not used');
    }

    /**
     * @param string $key
     * @param bool   $sessionTokenPrefix
     *
     * @return mixed|null
     */
    protected function _getCache($key, $sessionTokenPrefix = true)
    {
        if ($this->logger) $this->logger->debug(__METHOD__.' called', ['key' => $key, 'sessionTokenPrefix' => $sessionTokenPrefix]);

        if ($sessionTokenPrefix) {
            $key = $this->getSession()->getToken().'_'.$key;
        }

        if ($this->cache) {
            if ($this->logger) $this->logger->debug(__METHOD__.' get', ['key' => $key]);
            $result = $this->cache && $this->cache->get($key) ? $this->cache->get($key) : null;
            if ($this->logger) $this->logger->debug(__METHOD__.' result', ['data' => $result]);

            return $result;
        }

        if ($this->logger) $this->logger->debug(__METHOD__.' cache not used');
    }

    /**
     * @param string $key
     * @param bool   $sessionTokenPrefix
     *
     * @return mixed|null
     */
    protected function _deleteCache($key, $sessionTokenPrefix = true)
    {
        if ($sessionTokenPrefix) {
            $key = $this->getSession()->getToken().'_'.$key;
        }

        if ($this->cache) {
            if ($this->logger) $this->logger->debug(__METHOD__, ['key' => $key]);
            $result = $this->cache->delete($key);
            if ($this->logger) $this->logger->debug(__METHOD__.' result', ['data' => $result]);

            return $result;
        }

        if ($this->logger) $this->logger->debug(__METHOD__.' cache not used');
    }
}