<?php

namespace Fruitware\GabrielApi;

use Fruitware\GabrielApi\Exception\BadResponseException;
use Fruitware\GabrielApi\Model\CustomerInterface;
use Fruitware\GabrielApi\Model\PassengerInterface;
use Fruitware\GabrielApi\Model\PaymentInterface;
use Fruitware\GabrielApi\Model\SearchInterface;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Event\BeforeEvent;

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
     * @var string
     */
    protected $defaultLang = SearchInterface::LANG_RO;

    /**
     * @param array            $config
     * @param SessionInterface $session
     * @param CacheInterface   $cache
     *
     * @throws \Exception
     */
    public function __construct(array $config, SessionInterface $session, CacheInterface $cache = null)
    {
        parent::__construct($config);

        $this->setDefaultOption('headers', [
            'Content-Type' => 'application/json; charset=utf-8'
        ]);

        $this->session = $session;
        $this->cache = $cache;
        $this->guzzleClient = new GuzzleClient($this, new Description(), $config);

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
                $token = $this->getGuzzleClient()->login([
                    'login'    => $this->getSession()->getLogin(),
                    'password' => $this->getSession()->getPassword()
                ]);
                $this->getSession()->setToken($token);
            }
            catch (\Exception $ex) {
                if ($retry) {
                    $this->login(false);
                }

                throw $ex;
            }

            $this->setCache('lang', $this->defaultLang, 10);
        }

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
        if (is_null($city)) $city = '';
        $cacheKey = 'city_'.$city;
        $result = $this->getCache($cacheKey, false);
        if (!$result) {
            $result = $this->getGuzzleClient()->getCityName(['city' => $city]);
            $this->setCache($cacheKey, $result, 60);
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
    public function getAirlines($airline = null)
    {
        if (is_null($airline)) $airline = '';
        $cacheKey = 'airline_'.$airline;
        $result = $this->getCache($cacheKey, false);
        if (!$result) {
            $result = $this->getGuzzleClient()->getAirlineName(['airline' => $airline]);
            $this->setCache($cacheKey, $result, 60);
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
    public function getCounties($country = null)
    {
        if (is_null($country)) $country = '';
        $cacheKey = 'country_'.$country;
        $result = $this->getCache($cacheKey, false);
        if (!$result) {
            $result = $this->getGuzzleClient()->getCountryName(['country' => $country]);
            $this->setCache($cacheKey, $result, 60);
        }

        return $result;
    }

    /**
     * Get current booking info (segments, customer, passengers, total cost)
     *
     * @return array
     */
    public function getCurrentBooking()
    {
        return $this->getGuzzleClient()->getCurrentBooking();
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
        if ($search->getLang() !== $this->getCache('lang')) {
            $this->getGuzzleClient()->changeLanguage(['culture_code' => $search->getLang()]);
            $this->setCache('lang', $search->getLang(), 10);
        }

        $passengers = [
            'seats'    => $search->getAdults() + $search->getChildren(),
            'children' => $search->getChildren(),
            'infants'  => $search->getInfants()
        ];

        if ($passengers !== $this->getCache('numberOfPassengers')) {
            var_dump('numberOfPassengers');
            $this->getGuzzleClient()->setNumberOfPassengers($passengers);
            $this->setCache('numberOfPassengers', $passengers, 10);
            $this->deleteCache('getSegments');
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
            var_dump('getSegmentsArgs');
            $getSegments = $this->getGuzzleClient()->getSegments($newGetSegmentsArgs);
            $this->setCache('getSegmentsArgs', $newGetSegmentsArgs, 10);
            $this->setCache('getSegments', $getSegments, 10);
        }
        else {
            $getSegments = $this->getCache('getSegments');
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

    }

    /**
     * @param CustomerInterface $customer
     */
    public function setCustomer(CustomerInterface $customer)
    {
        $setCustomer = $this->getCache('setCustomer');

        $setNewCustomer = [
            'first_name' => $customer->getFirstName(),
            'last_name' => $customer->getLastName(),
            'title' => $customer->getTitle(),
            'gender' => $customer->getGender(),
            'birth_date' => $customer->getBirthDate()->format('Y-m-d'),
            'Passport' => $customer->getPassport(),
            'PassportIssued' => $customer->getPassportIssued()->format('Y-m-d'),
            'PassportExpire' => $customer->getPassportExpire()->format('Y-m-d'),
            'PassportCountry' => $customer->getPassportCountry(),
            'Contact' => $customer->getPhone(),
            'Email' => $customer->getEmail()
        ];

        if ($setCustomer !== $setNewCustomer) {
            $this->getGuzzleClient()->setCustomer($setNewCustomer);
            $this->setCache('setCustomer', $setNewCustomer, 10);
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

            $setNewPassengers[] = [
                'passenger_id' => $passenger->getPassengerId(),
                'first_name' => $passenger->getFirstName(),
                'last_name' => $passenger->getLastName(),
                'title' => $passenger->getTitle(),
                'gender' => $passenger->getGender(),
                'birth_date' => $passenger->getBirthDate()->format('Y-m-d'),
                'Passport' => $passenger->getPassport(),
                'PassportIssued' => $passenger->getPassportIssued()->format('Y-m-d'),
                'PassportExpire' => $passenger->getPassportExpire()->format('Y-m-d'),
                'PassportCountry' => $passenger->getPassportCountry(),
                'Contact' => $passenger->getPhone(),
                'Email' => $passenger->getEmail()
            ];
        }

        $setPassengers = $this->getCache('setPassengers');

        if ($setPassengers !== $setNewPassengers) {
            $this->getGuzzleClient()->setPassengers([
                'passengers' => json_encode($setNewPassengers)
            ]);
            $this->setCache('setPassengers', $setNewPassengers, 10);
        }
    }

    /**
     * Set payment type: CA – cash, CC – credit card, IN – invoice
     *
     * @param string $type
     */
    public function setPayment($type)
    {
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
    }

    /**
     * Confirm reservation
     */
    public function finalizeBooking()
    {
        $this->getGuzzleClient()->finalizeBooking();
    }

    /**
     * Start new booking session (Session Flush) - resets all data previously recorded in the number of session
     *
     * @return string New session token
     */
    public function reset()
    {
        $token = $this->getGuzzleClient()->reset();
        $this->deleteCache('lang');
        $this->deleteCache('numberOfPassengers');
        $this->deleteCache('getSegmentsArgs');
        $this->deleteCache('getSegments');
        $this->deleteCache('setSegments');
        $this->deleteCache('setCustomer');
        $this->deleteCache('setPassengers');
        $this->deleteCache('setPayment');

        $this->getSession()->setToken($token);

        return $this->getSession()->getToken();
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @param int    $minutes
     * @param bool   $sessionTokenPrefix
     *
     * @return mixed|bool
     */
    protected function setCache($key, $value, $minutes, $sessionTokenPrefix = true)
    {
        if ($this->cache) {
            if ($sessionTokenPrefix) {
                $key = $this->getSession()->getToken().'_'.$key;
            }

            return $this->cache->set($key, $value, $minutes);
        }

        return false;
    }

    /**
     * @param string $key
     * @param bool   $sessionTokenPrefix
     *
     * @return mixed|null
     */
    protected function getCache($key, $sessionTokenPrefix = true)
    {
        if ($sessionTokenPrefix) {
            $key = $this->getSession()->getToken().'_'.$key;
        }

        return $this->cache && $this->cache->get($key) ? $this->cache->get($key) : null;
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
            $this->cache->delete($key);
        }
    }
}