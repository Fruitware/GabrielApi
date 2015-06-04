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
$client = new Client(['base_url' => 'http://b2bportal.demo.qsystems.md/']); #demo server
$guzzleClient = $client->getClient();
# before other methods just call login method
$result = $guzzleClient->login(['login' => 'YOUR_LOGIN', 'password' => 'YOUR_PASSWORD']);

var_dump(
    'getSupportedLanguages', $guzzleClient->getSupportedLanguages(),
	'changeLanguage', $guzzleClient->changeLanguage(['culture_code' => 'ru-RU']),
	'getSupportedLanguages', $guzzleClient->getSupportedLanguages(),
	'getCountryName', $guzzleClient->getCountryName(['country' => 'ML'])
);
```

## All methods

```php
/**
 * @method array getSupportedLanguages()
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
 * @method array flush() - resets all data and start new session
 */
```