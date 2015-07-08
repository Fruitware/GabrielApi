<?php

namespace Fruitware\GabrielApi;

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
     * @param Session $session
     * @param array   $config
     */
    public function __construct(Session $session, array $config = [])
    {
        parent::__construct($config);

        $this->setDefaultOption('headers', [
            'Content-Type' => 'application/json; charset=utf-8'
        ]);

        $this->session = $session;
        $this->guzzleClient = new GuzzleClient($this, new Description(), $config);

        $this->login();

        $this->getEmitter()->on('before', function (BeforeEvent $e) {
            if ($this->getSession()->getToken()) {
                $query = $e->getRequest()->getQuery();
                $query->add('session_id', $this->getSession()->getToken());
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
     * @return string Session token
     */
    public function login()
    {
        if (!$this->getSession()->getToken()) {
            $token = $this->getGuzzleClient()->login([
                'login'    => $this->getSession()->getLogin(),
                'password' => $this->getSession()->getPassword()
            ]);
            $this->getSession()->setToken($token);
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
    public function getCities($city = '')
    {
        return $this->getGuzzleClient()->getCityName(['city' => $city]);
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
        $this->getGuzzleClient()->changeLanguage(['culture_code' => $search->getLang()]);
        $this->getGuzzleClient()->setNumberOfPassengers([
            'seats'    => $search->getAdults() + $search->getChildren(),
            'children' => $search->getChildren(),
            'infants'  => $search->getInfants()
        ]);

        return $this->getGuzzleClient()->getSegments([
            'airport_from'  => $search->getDepartureAirport(),
            'airport_to'    => $search->getArrivalAirport(),
            'dep_date'      => $search->getDepartureDate()->format('Y-m-d'),
            'ret_date'      => $search->getReturnDate() ? $search->getReturnDate()->format('Y-m-d') : null,
            'search_option' => $search->getSearchOption()
        ]);
    }

    /**
     * @param int      $optionId     Selected price option
     * @param null|int $optionIdBack Selected price option back if is roundtrip search
     * @param int      $searchOption Reference to searching identifier search_option
     */
    public function setSegment($optionId, $optionIdBack = null, $searchOption = 1)
    {
        $this->getGuzzleClient()->setSegment([
            'option_id' => $optionId,
            'option_id_back' => $optionIdBack,
            'search_option' => $searchOption,
        ]);
    }

    /**
     * @param CustomerInterface $customer
     */
    public function setCustomer(CustomerInterface $customer)
    {
        $this->getGuzzleClient()->setCustomer([
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
        ]);
    }

    /**
     * @param \Iterator $passengersIterator
     */
    public function setPassengers(\Iterator $passengersIterator)
    {
        $passengers = [];
        /** @var PassengerInterface $passenger */
        foreach ($passengersIterator as $passenger) {
            if (!$passenger instanceof PassengerInterface) {
                throw new \InvalidArgumentException('passenger must me instance of PassengerInterface');
            }

            $passengers[] = [
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

        $this->getGuzzleClient()->setPassengers([
            'passengers' => json_encode($passengers)
        ]);
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

        $this->getGuzzleClient()->setFormOfPayment([
            'form_of_payment' => $type
        ]);
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
        $this->getSession()->setToken($token);

        return $this->getSession()->getToken();
    }
}