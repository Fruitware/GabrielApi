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
namespace MyProject;

use Fruitware\GabrielApi\Client;
use Fruitware\GabrielApi\Model\Customer;
use Fruitware\GabrielApi\Model\CustomerInterface;
use Fruitware\GabrielApi\Model\Passenger;
use Fruitware\GabrielApi\Model\PassengerInterface;
use Fruitware\GabrielApi\Model\PassengerIterator;
use Fruitware\GabrielApi\Model\Search;
use Fruitware\GabrielApi\Model\SearchInterface;
use Fruitware\GabrielApi\Session;
use GuzzleHttp\Subscriber\Log\Formatter;
use GuzzleHttp\Subscriber\Log\LogSubscriber;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Example 
{
    // init Client
    Session::setCredentials('YOUR_LOGIN', 'YOUR_PASSWORD');
    $client = new Client(new Session(), ['base_url' => 'https://b2b.airmoldova.md/']);

    // set cache, if you want (your class must implement Fruitware\GabrielApi\CacheInterface)
    $client->setCache(new Cache());

    // create a log channel for guzzle requests, if you want
    $log = new Logger('gabriel_guzzle_request');
    $log->pushHandler(new StreamHandler(__DIR__.'/logs/gabriel_guzzle_request.log', Logger::DEBUG));
    $subscriber = new LogSubscriber($log, Formatter::SHORT);
    $client->getEmitter()->attach($subscriber);

    // create a log for client class, if you want
    $logger = new Logger('gabriel_api');
    $stream = new StreamHandler(__DIR__.'/logs/gabriel_api.log', Logger::DEBUG);
    $output = "%extra.token%: [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
    $formatter = new LineFormatter($output, 'Y-m-d H:i:s');
    $stream->setFormatter($formatter);
    $logger->pushHandler($stream);
    $client->setLogger($logger);

    // 1. if you already have a session token, you can set it, otherwise a new session token generated automatically
    $client->getSession()->setToken('6215de8e576345808664376876ec9be4');
    
    // 2. search segments (Will generate a session token, if it was not existed)
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
        ->setMobilePhone('+37322123456')
        ->setEmail('example@example.com')
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
        ->setEmail('example@example.com')
    ;
    $client->setCustomer($customer);
    var_dump($client->getCurrentBooking());
    
    // 6. set payment type
    $client->setPayment(PaymentInterface::TYPE_INVOICE);
    var_dump($client->getCurrentBooking());
    
    // 7. Confirm reservation
    $client->finalizeBooking();
    var_dump($client->getCurrentBooking());
    
    // 8. If you want cancel reservation
    $newSessionToken = $client->reset();
    var_dump($newSessionToken); // or var_dump($client->getSession()->getToken());
}
```

## All high level methods
```php
/**
 * @method string login() - Generate new session token if not exist
 * @method array getCities($city = null) - Get all cities or find by partial name or ISO code of searching city
 * @method array getAirlines($airline = null) - Get all airlines or find by partial name or IATA code of searching airline
 * @method array getCounties($country = null) - Get all countries or find by partial name or ISO code of searching country
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
 var_dump($guzzleClient->getSupportedLanguages());
```