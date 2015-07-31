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

use Fruitware\GabrielApi\GabrielClient;
use Fruitware\GabrielApi\Model\Customer;
use Fruitware\GabrielApi\Model\CustomerInterface;
use Fruitware\GabrielApi\Model\Passenger;
use Fruitware\GabrielApi\Model\PassengerInterface;
use Fruitware\GabrielApi\Model\PassengerIterator;
use Fruitware\GabrielApi\Model\Search;
use Fruitware\GabrielApi\Model\SearchInterface;
use Fruitware\GabrielApi\Session;
use GuzzleHttp\Client;
use GuzzleHttp\Subscriber\Log\Formatter;
use GuzzleHttp\Subscriber\Log\LogSubscriber;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Example 
{
    // init Client
    Session::setCredentials('YOUR_LOGIN', 'YOUR_PASSWORD');
    $guzzleClient = new Client(['base_url' => 'https://b2b.airmoldova.md/']);
    $client = new GabrielClient($guzzleClient);

    // set cache, if you want (your class must implement Fruitware\GabrielApi\CacheInterface)
    $client->setCache(new Cache());

    // create a log channel for guzzle requests, if you want
    $log = new Logger('gabriel_guzzle_request');
    $log->pushHandler(new StreamHandler(__DIR__.'/logs/gabriel_guzzle_request.log', Logger::DEBUG));
    $subscriber = new LogSubscriber($log, Formatter::SHORT);
    $client->getHttpClient()->getEmitter()->attach($subscriber);

    // create a log for client class, if you want (monolog/monolog required)
    $logger = new Logger('gabriel_api');
    $stream = new StreamHandler(__DIR__.'/logs/gabriel_api.log', Logger::DEBUG);
    $output = "%extra.token%: [%datetime%] %channel%.%level_name%: %message% %context% %extra%\n";
    $stream->setFormatter(new LineFormatter($output, 'Y-m-d H:i:s'));
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
        ->setPassportCountry('MD')
        ->setPassportIssued(new \DateTime('2014-01-10'))
        ->setPassportExpire(new \DateTime('2017-01-10'))
        ->setMobilePhone('+37322123456')
        ->setEmail('example@example.com')
    ;
    $passengersIterator->add($passenger);
    $client->setPassengers($passengersIterator);

    // 6. set customer
    // 6.1 one of the passengers can be the customer, if you already call setPassengers method
    $client->setCustomer($passenger);

    // 6.2 or you can create a customer object
    $customer = new Customer();
    $customer
        ->setFirstName('firstName')
        ->setLastName('lastName')
        ->setTitle(CustomerInterface::TITLE_MR)
        ->setGender(CustomerInterface::GENDER_MALE)
        ->setBirthDate(new \DateTime('1977-01-01'))
        ->setPassport('A0000001')
        ->setPassportCountry('MD')
        ->setPassportIssued(new \DateTime('2014-01-10'))
        ->setPassportExpire(new \DateTime('2017-01-10'))
        ->setMobilePhone('+37322123456')
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