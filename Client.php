<?php

namespace Fruitware\GabrielApi;

use Fruitware\GabrielApi\Exception\BadResponseException;
use Fruitware\GabrielApi\Model\CustomerInterface;
use Fruitware\GabrielApi\Model\PassengerInterface;
use Fruitware\GabrielApi\Model\PaymentInterface;
use Fruitware\GabrielApi\Model\SearchInterface;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Event\BeforeEvent;
use Psr\Log\LoggerInterface;

class Client extends GuzzleHttpClient
{
    /**
     * @var GuzzleClient
     */
    protected $guzzleClient;

    /**
     * @var Session
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
     * @param array            $config
     * @param SessionInterface $session
     * @param CacheInterface   $cache
     * @param LoggerInterface  $logger
     */
    public function __construct(array $config, SessionInterface $session, CacheInterface $cache = null, LoggerInterface $logger = null)
    {
        parent::__construct($config);

        $this->setDefaultOption('headers', [
            'Content-Type' => 'application/json; charset=utf-8'
        ]);

        $this->session = $session;
        $this->cache = $cache;
        $this->logger = $logger;
        $this->guzzleClient = new GuzzleClient($this, new Description(), $config, $this->logger);

        $this->getEmitter()->on('before', function (BeforeEvent $e) {
            if ($e->getRequest()->getPath() !== '/GabrielAPI/Account/GetTicket') {
                if (!$this->getSession()->getToken()) {
                    $this->login();
                }

                if ($this->getSession()->getToken()) {
                    $query = $e->getRequest()->getQuery();
                    $query->add('session_id', $this->getSession()->getToken());
                }
                else {
                    throw new BadResponseException('Request require session token');
                }
            }
        });

        if ($this->logger) {
            $this->logger->pushProcessor(function ($record) {
                $token = $this->getSession()->getToken();
                $record['extra']['token'] = $token;

                return $record;
            });
        }
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
     * Get low level client
     *
     * @return GuzzleClient
     */
    public function getGuzzleClient()
    {
        return $this->guzzleClient;
    }

    /**
     * Start new session if token doesn't exist
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
                if ($this->logger) $this->logger->debug(__METHOD__.' called');
                $token = $this->getGuzzleClient()->login([
                    'login'    => $this->getSession()->getLogin(),
                    'password' => $this->getSession()->getPassword()
                ]);
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

            $this->setCache('lang', $this->defaultLang, 10);
        }

        if ($this->logger) $this->logger->info(__METHOD__.' result', ['data' => $this->getSession()->getToken()]);

        return $this->getSession()->getToken();
    }

    /**
     * Get all cities or find by partial name or ISO code of searching city
     *
     * @param string $city Partial name or ISO code of searching city or empty for get all cities
     *
     * @return array
     */
    public function getCities($city = null)
    {
        if ($this->logger) $this->logger->debug(__METHOD__.' called', ['city' => $city]);
        if (is_null($city)) $city = '';
        $cacheKey = 'city_'.$city;
        $result = $this->getCache($cacheKey, false);
        if (!$result) {
            $args = ['city' => $city];
            $result = $this->getGuzzleClient()->getCityName($args);
            $this->setCache($cacheKey, $result, 60);
        }

        if ($this->logger) $this->logger->info(__METHOD__.' result', ['data' => $result]);

        return $result;
    }

    /**
     * Get all airlines or find by partial name or IATA code of searching airline
     *
     * @param string $airline Partial name or IATA code of searching airline or empty for get all airlines
     *
     * @return array
     */
    public function getAirlines($airline = null)
    {
        if ($this->logger) $this->logger->debug(__METHOD__.' called', ['airline' => $airline]);
        if (is_null($airline)) $airline = '';
        $cacheKey = 'airline_'.$airline;
        $result = $this->getCache($cacheKey, false);
        if (!$result) {
            $args = ['airline' => $airline];
            $result = $this->getGuzzleClient()->getAirlineName($args);
            $this->setCache($cacheKey, $result, 60);
        }

        if ($this->logger) $this->logger->info(__METHOD__.' result', ['data' => $result]);

        return $result;
    }

    /**
     * Get all countries or find by partial name or ISO code of searching country
     *
     * @param string $country Partial name or ISO code of searching country or empty for get all countries
     *
     * @return array
     */
    public function getCounties($country = null)
    {
        if ($this->logger) $this->logger->debug(__METHOD__.' called', ['country' => $country]);
        if (is_null($country)) $country = '';
        $cacheKey = 'country_'.$country;
        $result = $this->getCache($cacheKey, false);
        if (!$result) {
            $args = ['country' => $country];
            $result = $this->getGuzzleClient()->getCountryName($args);
            $this->setCache($cacheKey, $result, 60);
        }

        if ($this->logger) $this->logger->info(__METHOD__.' result', ['data' => $result]);

        return $result;
    }

    /**
     * Get current booking info (segments, customer, passengers, total cost)
     *
     * @return array
     */
    public function getCurrentBooking()
    {
        if ($this->logger) $this->logger->debug(__METHOD__.' called');
        $result = $this->getGuzzleClient()->getCurrentBooking();

        if ($this->logger) $this->logger->info(__METHOD__.' result', ['data' => $result]);

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
        if ($search->getLang() !== $this->getCache('lang')) {
            $args = ['culture_code' => $search->getLang()];
            $this->getGuzzleClient()->changeLanguage($args);
            $this->setCache('lang', $search->getLang(), 10);
        }
        else {
            if ($this->logger) $this->logger->debug(__METHOD__.' -> lang used from the cache');
        }

        $passengers = [
            'seats'    => $search->getAdults() + $search->getChildren(),
            'children' => $search->getChildren(),
            'infants'  => $search->getInfants()
        ];

        if ($passengers !== $this->getCache('numberOfPassengers')) {
            $this->getGuzzleClient()->setNumberOfPassengers($passengers);
            $this->setCache('numberOfPassengers', $passengers, 10);
            $this->deleteCache('getSegments');
        }
        else {
            if ($this->logger) $this->logger->debug(__METHOD__.' -> numberOfPassengers used from the cache');
        }

        $newGetSegmentsArgs = [
            'airport_from'  => $search->getDepartureAirport(),
            'airport_to'    => $search->getArrivalAirport(),
            'dep_date'      => $search->getDepartureDate()->format('Y-m-d'),
            'ret_date'      => $search->getReturnDate() ? $search->getReturnDate()->format('Y-m-d') : null,
            'search_option' => $search->getSearchOption()
        ];

        $getSegmentsArgs = $this->getCache('getSegmentsArgs');
        if ($newGetSegmentsArgs !== $getSegmentsArgs || !$this->getCache('getSegments')) {
            $getSegments = $this->getGuzzleClient()->getSegments($newGetSegmentsArgs);
            $this->setCache('getSegmentsArgs', $newGetSegmentsArgs, 5);
            $this->setCache('getSegments', $getSegments, 5);
        }
        else {
            $getSegments = $this->getCache('getSegments');
            if ($this->logger) $this->logger->debug(__METHOD__.' -> getSegments used from the cache');
        }

        if ($this->logger) $this->logger->info(__METHOD__.' result', ['data' => $getSegments]);

        return $getSegments;
    }

    /**
     * @param int      $optionId     Selected price option
     * @param null|int $optionIdBack Selected price option back if is roundtrip search
     * @param int      $searchOption Reference to searching identifier search_option
     */
    public function setSegment($optionId, $optionIdBack = null, $searchOption = 1)
    {
        if ($this->logger) $this->logger->debug(__METHOD__.' called', ['optionId' => $optionId, 'optionIdBack' => $optionIdBack, 'searchOption' => $searchOption]);

        $setSegments = $this->getCache('setSegments');

        $segments = [
            'option_id' => $optionId,
            'option_id_back' => $optionIdBack,
            'search_option' => $searchOption,
        ];

        if ($setSegments !== $segments) {
            $this->getGuzzleClient()->setSegment($segments);
            $this->setCache('setSegments', $segments, 10);
        }
        else {
            if ($this->logger) $this->logger->debug(__METHOD__.' used from the cache');
        }

        if ($this->logger) $this->logger->info(__METHOD__.' result');
    }

    /**
     * @param CustomerInterface $customer
     */
    public function setCustomer(CustomerInterface $customer)
    {
        $setNewCustomer = $customer->toArray();
        if ($this->logger) $this->logger->debug(__METHOD__.' called', ['customer' => $setNewCustomer]);

        $setCustomer = $this->getCache('setCustomer');

        if ($setCustomer !== $setNewCustomer) {
            $this->getGuzzleClient()->setCustomer($setNewCustomer);
            $this->setCache('setCustomer', $setNewCustomer, 10);
        }
        else {
            if ($this->logger) $this->logger->debug(__METHOD__.' used from the cache');
        }

        if ($this->logger) $this->logger->info(__METHOD__.' result');
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

        if ($this->logger) $this->logger->debug(__METHOD__.' called', ['passengers' => $setNewPassengers]);

        $setPassengers = $this->getCache('setPassengers');

        if ($setPassengers !== $setNewPassengers) {
            $this->getGuzzleClient()->setPassengers([
                'passengers' => json_encode($setNewPassengers)
            ]);
            $this->setCache('setPassengers', $setNewPassengers, 10);
        }
        else {
            if ($this->logger) $this->logger->debug(__METHOD__.' used from the cache');
        }

        if ($this->logger) $this->logger->info(__METHOD__.' result');
    }

    /**
     * Set payment type: CA – cash, CC – credit card, IN – invoice
     *
     * @param string $type
     */
    public function setPayment($type)
    {
        if ($this->logger) $this->logger->debug(__METHOD__.' called', ['type' => $type]);

        if (!in_array($type, [PaymentInterface::TYPE_CASH, PaymentInterface::TYPE_CREDIT_CARD, PaymentInterface::TYPE_INVOICE])) {
            throw new \InvalidArgumentException('type '.$type.' is not valid');
        }

        $setPayment = $this->getCache('setPayment');

        if ($setPayment !== $type) {
            $this->getGuzzleClient()->setFormOfPayment([
                'form_of_payment' => $type
            ]);
            $this->setCache('setPayment', $type, 10);
        }
        else {
            if ($this->logger) $this->logger->debug(__METHOD__.' used from the cache');
        }

        if ($this->logger) $this->logger->info(__METHOD__.' result');
    }

    /**
     * Confirm reservation
     */
    public function finalizeBooking()
    {
        if ($this->logger) $this->logger->debug(__METHOD__.' called');

        $this->getGuzzleClient()->finalizeBooking();

        if ($this->logger) $this->logger->info(__METHOD__.' result');
    }

    /**
     * Start new booking session (Session Flush) - resets all data previously recorded in the number of session
     *
     * @return string New session token
     */
    public function reset()
    {
        if ($this->logger) $this->logger->debug(__METHOD__.' called');

        $newToken = $this->getGuzzleClient()->reset();
        $this->deleteCache('lang');
        $this->deleteCache('numberOfPassengers');
        $this->deleteCache('getSegmentsArgs');
        $this->deleteCache('getSegments');
        $this->deleteCache('setSegments');
        $this->deleteCache('setCustomer');
        $this->deleteCache('setPassengers');
        $this->deleteCache('setPayment');

        if ($this->logger) $this->logger->info(__METHOD__.' result', ['token' => $newToken]);
        $this->getSession()->setToken($newToken);

        return $this->getSession()->getToken();
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @param int    $minutes
     * @param bool   $sessionTokenPrefix
     *
     * @return mixed|null
     */
    protected function setCache($key, $value, $minutes, $sessionTokenPrefix = true)
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
    protected function getCache($key, $sessionTokenPrefix = true)
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
    protected function deleteCache($key, $sessionTokenPrefix = true)
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