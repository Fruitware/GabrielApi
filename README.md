Gabriel Api
=======================

JSON Protocol B2B Portal project is created to organize ticket sales online on the websites of Agencies

## Installing

```bash
composer require fruitware/gabriel-api
```

## Help and docs

- [Documentation](https://github.com/Fruitware/GabrielApi/blob/master/docs/9U_Qsystem_B2B_portal.pdf)

## Usage

```php
Session::setCredentials('YOUR_LOGIN', 'YOUR_PASSWORD');
// if you already have session token, you can set it, otherwise a new session token generated automatically
// Example with the demo server, but with real booking :)
$client = new Client(['base_url' => 'http://b2bportal.demo.qsystems.md/'], new Session(), new Cache()); 

// 1. Use existing session token if you have
$client->getSession()->setToken('6215de8e576345808664376876ec9be4');

// 2. search segments (Will generate session token, if was not existed)
$search = new Search();
$search
    ->setLang(SearchInterface::LANG_EN)
    ->setDepartureAirport('KIV')
    ->setArrivalAirport('MOW')
    ->setDepartureDate((new \DateTime())->modify('+7 days'))
    ->setReturnDate((new \DateTime())->modify('+14 days'))
    ->setAdults(1) // default 1
    ->setChildren(0) // default 0
    ->setInfants(0) // default 0
    ->setSearchOption(1) // default 1
;
$segments = $client->search($search);
var_dump($segments);

// 3. get current session token
var_dump($client->getSession()->getToken());

// 4. set segments (option_id, option_id_back)
$client->setSegment(20, 36);
var_dump($client->getCurrentBooking());

// 5. set passengers
$passengersIterator = new PassengerIterator();
$passenger = new Passenger();
$passenger
    ->setPassengerId(0)
    ->setFirstName('firstName')
    ->setLastName('lastName')
    ->setTitle(PassengerInterface::TITLE_MR)
    ->setGender(PassengerInterface::GENDER_MALE)
    ->setBirthDate(new \DateTime('1977-01-01'))
    ->setPassport('A0000001')
    ->setPassportIssued(new \DateTime('2014-01-10'))
    ->setPassportExpire(new \DateTime('2017-01-10'))
    ->setMobilePhone('+37322888333')
    ->setEmail('some-example@example.com')
;
$passengersIterator->add($passenger);
$client->setPassengers($passengersIterator);

$customer = new Customer();
$customer
    ->setFirstName('firstName')
    ->setLastName('lastName')
    ->setTitle(CustomerInterface::TITLE_MR)
    ->setGender(CustomerInterface::GENDER_MALE)
    ->setBirthDate(new \DateTime('1977-01-01'))
    ->setPassport('A0000001')
    ->setPassportIssued(new \DateTime('2014-01-10'))
    ->setPassportExpire(new \DateTime('2017-01-10'))
    ->setMobilePhone('+37322888333')
    ->setEmail('some-example@example.com')
;
$client->setCustomer($customer);
var_dump($client->getCurrentBooking());

// 6. set payment type
$client->setPayment(PaymentInterface::TYPE_INVOICE);
var_dump($client->getCurrentBooking());

// 7. Confirm reservation
$client->finalizeBooking();

// 8. If you want cancel reservation
$newSessionToken = $client->reset();
var_dump($newSessionToken); // or var_dump($client->getSession()->getToken());
```

## All high level methods
```php
/**
 * @method string login() - Generate new session token if not exist
 * @method array getCities($city = '') - Get all cities or find by partial name or ISO code of searching city
 * @method array getCurrentBooking() - Get current booking info (segments, customer, passengers, total cost)
 * @method array search(SearchInterface $search) - Search segments
 * @method void setSegment($optionId, $optionIdBack = null, $searchOption = 1)
 * @method void setCustomer(CustomerInterface $customer)
 * @method void setPassengers(\Iterator $passengersIterator)
 * @method void setPayment($type) - Set payment type: CA – cash, CC – credit card, IN – invoice
 * @method void finalizeBooking() - Confirm reservation
 * @method string reset() - Start new booking session (Session Flush) - resets all data previously recorded in the number of session
 */
```

## All low level methods (See arguments in Fruitware\GabrielApi\Description class)
```php
/**
 * @method array login() - get session token
 * @method array reset() - resets all data and start new session
 * @method array getSupportedLanguages()
 * @method array changeLanguage(array $args) 
 * @method array getCountryName(array $args)
 * @method array getCityName(array $args)
 * @method array getAirlineName(array $args)
 * @method array getBestPrice()
 * @method array getCalendarShopping(array $args)
 * @method array getCurrencyExchange(array $args)
 * @method array getDefaultSearchSettings()
 * @method array setNumberOfPassengers(array $args)
 * @method array getSegments(array $args)
 * @method array getSegmentsSearchHistory(array $args)
 * @method array setSegment(array $args)
 * @method array clearSegments(array $args)
 * @method array getTotalCost()
 * @method array setCustomer(array $args)
 * @method array setPassengers(array $args)
 * @method array setFormOfPayment(array $args)
 * @method array getCurrentBooking()
 * @method array finalizeBooking() - confirm booking
 */
 
 // example
 $guzzleClient = $client->getGuzzleClient(); 
 $guzzleClient->getSupportedLanguages();
```