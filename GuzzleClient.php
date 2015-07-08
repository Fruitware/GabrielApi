<?php

namespace Fruitware\GabrielApi;

use Fruitware\GabrielApi\Exception\BadResponseException;
use GuzzleHttp\ClientInterface;

/**
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
 * @method array finalizeBooking() - confirm
 */
class GuzzleClient extends \GuzzleHttp\Command\Guzzle\GuzzleClient
{
    /**
     * Authenticate and obtain a work session in B2B Portal system.
     *
     * @param array $args
     */
    public function login(array $args = [])
    {
        $result = parent::login($args);

        return $result['session_id'];
    }

    /**
     * Start new booking session (Session Flush) - resets all data previously recorded in the number of session
     *
     * @return string
     */
    public function reset()
    {
        $result = parent::reset();

        return $result['session_id'];
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return array
     */
    public function __call($name, array $arguments)
    {
        $response = parent::__call($name, $arguments);

        if ($this->checkSuccessResponse($response)) {
            return $response['result'];
        }

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
}