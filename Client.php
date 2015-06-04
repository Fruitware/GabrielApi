<?php

namespace Fruitware\GabrielApi;

class Client extends \GuzzleHttp\Client
{
    /** @var GuzzleClient  */
    protected $client;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->setDefaultOption('headers', [
            'Content-Type' => 'application/json; charset=utf-8'
        ]);
        $this->client = new GuzzleClient($this, $config);
    }

    /**
     * @return GuzzleClient
     */
    public function getClient()
    {
        return $this->client;
    }
}