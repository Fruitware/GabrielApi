<?php

namespace Fruitware\GabrielApi;

use Fruitware\GabrielApi\Exception\BadResponseException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Event\BeforeEvent;

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
 * @method array finalizeBooking() - confirm
 */
class GuzzleClient extends \GuzzleHttp\Command\Guzzle\GuzzleClient
{
    /** @var GuzzleClient  */
    protected $client;

    /**
     * @var string
     */
    protected $sessionId;

    public function __construct(ClientInterface $client, array $config = [])
    {
        parent::__construct($client, new Description(), $config);

        $this->client = $client;

        $client->getEmitter()->on('before', function (BeforeEvent $e) use($client) {
            if ($this->sessionId) {
                $query = $e->getRequest()->getQuery();
                $query->add('session_id', $this->sessionId);
            }
        });
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @param array $args
     */
    public function login(array $args = [])
    {
        $result = parent::login($args);

        $this->sessionId = $result['session_id'];
    }

    /**
     * @param array $args
     */
    public function flush()
    {
        $result = parent::flush($args);

        $this->sessionId = $result['session_id'];
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