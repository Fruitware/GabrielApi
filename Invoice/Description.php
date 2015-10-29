<?php

namespace Fruitware\GabrielApi\Invoice;

class Description extends \GuzzleHttp\Command\Guzzle\Description
{
    /**
     * @param array $options Custom options to apply to the description
     *     - formatter: Can provide a custom SchemaFormatter class
     */
    public function __construct(array $options = [])
    {
        parent::__construct([
            'name'        => 'Invoice API',
//            'baseUrl' => 'https://b2bportal.demo.qsystems.md/', // demo server
//            'baseUrl' => 'https://b2b.airmoldova.md/', // production server
            'operations' => [
                'get' => [
                    'httpMethod' => 'POST',
                    'uri' => '/InvoiceAPI/API/Get',
                    'description' => 'Get invoice info',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'login' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                        ],
                        'password' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                        ],
                        'OwnerKey' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Invoice number',
                        ],
                    ]
                ],
                'pay' => [
                    'httpMethod' => 'POST',
                    'uri' => '/InvoiceAPI/API/Pay',
                    'description' => 'Confirm invoice pay',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'login' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                        ],
                        'password' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                        ],
                        'OwnerKey' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Invoice number',
                        ],
                        'paymentTypeCode' => [
                            'type' => 'integer',
                            'location' => 'json',
                            'required' => true,
                            'description' => '0 - cash, 1 - card, 2+ - reserved',
                            'example' => '0',
                        ],
                        'transactionNumber' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'receipt number for cash type or card number for card type',
                        ],
                    ]
                ],
            ],
            'models' => [
                'getResponse' => [
                    'type' => 'object',
                    'additionalProperties' => [
                        'location' => 'json'
                    ]
                ]
            ]
        ], $options);
    }
}