<?php

namespace Fruitware\GabrielApi\Invoice;

use Fruitware\GabrielApi\Exception\BadResponseException;
use Fruitware\GabrielApi\Model\Invoice;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Command\Guzzle\DescriptionInterface;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Stream\Stream;
use Psr\Log\LoggerInterface;

class Client extends \GuzzleHttp\Command\Guzzle\GuzzleClient
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param ClientInterface      $client
     * @param SessionInterface     $session
     * @param DescriptionInterface $description
     * @param array                $config
     */
    public function __construct(ClientInterface $client = null, SessionInterface $session = null, DescriptionInterface $description = null, array $config = [])
    {
        $this->session = $session instanceof SessionInterface  ? $session : new Session();
        $client = $client instanceof ClientInterface ? $client : new GuzzleClient();
        $description = $description instanceof DescriptionInterface ? $description : new Description();

        parent::__construct($client, $description, $config);

        $this->getHttpClient()->setDefaultOption('headers', [
            'Content-Type' => 'application/json; charset=utf-8'
        ]);

        $this->getHttpClient()->getEmitter()->on('before', function (BeforeEvent $event) {
            $result = json_decode((string)$event->getRequest()->getBody(), true);
            $result = is_array($result) ? $result : [];
            $result['login'] = $this->getSession()->getLogin();
            $result['password'] = $this->getSession()->getPassword();
            $stream = Stream::factory(json_encode($result));
            $event->getRequest()->setBody($stream);
        });
    }

    /**
     * Set logger (monolog required)
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
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
     * @param string $invoiceNumber
     *
     * @return Invoice
     */
    public function get($invoiceNumber)
    {
        $data = parent::get([
            'OwnerKey' => $invoiceNumber
        ]);

        return new Invoice($data);
    }

    /**
     * @param string  $invoiceNumber
     * @param integer $paymentTypeCode
     * @param string  $transactionNumber
     */
    public function pay($invoiceNumber, $paymentTypeCode, $transactionNumber)
    {
        parent::pay([
            'OwnerKey'          => $invoiceNumber,
            'paymentTypeCode'   => (int)$paymentTypeCode,
            'transactionNumber' => $transactionNumber,
        ]);
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
        return true;
    }
}