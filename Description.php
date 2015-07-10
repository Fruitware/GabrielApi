<?php

namespace Fruitware\GabrielApi;

class Description extends \GuzzleHttp\Command\Guzzle\Description
{
    /**
     * @param array $options Custom options to apply to the description
     *     - formatter: Can provide a custom SchemaFormatter class
     */
    public function __construct(array $options = [])
    {
        parent::__construct([
            'name'        => 'Gabriel API',
//            'baseUrl' => 'http://b2bportal.demo.qsystems.md/',
            'operations' => [
                'login' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Account/GetTicket',
                    'description' => 'Authenticate and obtain a work session in B2B Portal system.',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'login' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                        ],
                        'password' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                        ]
                    ]
                ],
                'reset' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Account/FlushSession',
                    'description' => 'Start new booking session (Session Flush) - resets all data previously recorded in the number of session',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                        ]
                    ]
                ],
                'getSupportedLanguages' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Settings/GetSupportedLanguages',
                    'responseModel' => 'getResponse',
                ],
                'changeLanguage' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Settings/ChangeTheSessionLanguage',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'culture_code' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Language culture code',
                            'example' => 'ru-RU'
                        ]
                    ]
                ],
                'getCountryName' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Info/GetCountryName',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'country' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Partial name or ISO code of searching country',
                            'example' => 'MD or MOL'
                        ]
                    ]
                ],
                'getCityName' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Info/GetCityName',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'city' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Partial name or ISO code of searching city',
                            'example' => 'KIV or CHI'
                        ]
                    ]
                ],
                'getAirlineName' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Info/GetAirlineName',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'airline' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Partial name or IATA code of searching airline',
                            'example' => '9U'
                        ]
                    ]
                ],
                'getBestPrice' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Info/GetBestPrice',
                    'responseModel' => 'getResponse',
                ],
                'getCalendarShopping' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Info/GetCalendarShopping',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'cityfrom' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Departure airport/city',
                        ],
                        'cityto' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Arrival airport/city',
                        ],
                        'datefrom' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => false,
                            'description' => 'Starting searching date',
                        ],
                        'dateto' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => false,
                            'description' => 'Ending searching date',
                        ],
                    ]
                ],
                'getCurrencyExchange' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Info/GetCurrencyExchange',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'fromcurr' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'From currency',
                            'example' => 'EUR',
                        ],
                        'tocurr' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'To currency',
                            'example' => 'MLD',
                        ],
                        'amount' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Amount to convert',
                            'example' => '100.01',
                        ]
                    ]
                ],
                'getDefaultSearchSettings' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Booking/GetDefaultSearchSettings',
                    'responseModel' => 'getResponse',
                ],
                'setNumberOfPassengers' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Booking/SetNumberOfPassengers',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'seats' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Passengers (total seats, including children)',
                        ],
                        'children' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Number of children (2 -12 years)',
                        ],
                        'infants' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Number of infants (under 2 years)',
                        ]
                    ]
                ],
                'getSegments' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Booking/GetSegments',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'airport_from' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Departure airport/city',
                            'example' => 'KIV',
                        ],
                        'airport_to' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Arrival airport/city',
                            'example' => 'MOW',
                        ],
                        'dep_date' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Departure date'
                        ],
                        'ret_date' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => false,
                            'description' => 'Return date'
                        ],
                        'search_option' => [
                            'type' => 'string',
                            'location' => 'query',
                            'default' => 1,
                            'required' => true,
                            'description' => 'Search identifier – ability to have more than one active searches'
                        ],
                    ]
                ],
                # custom method
                'getSegmentsSearchHistory' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Booking/GetSegments',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'search_option' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Search identifier – ability to have more than one active searches'
                        ],
                    ]
                ],
                'getFareNotes' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Booking/GetFareNotes',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'search_option' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Reference to searching identifier search_option'
                        ],
                        'option_id' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Reference to price option'
                        ],
                        'option_id_back' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => false,
                            'description' => 'Reference to price option back if is roundtrip search'
                        ],
                    ]
                ],
                'setSegment' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Booking/SetSegment',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'search_option' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Reference to searching identifier search_option'
                        ],
                        'option_id' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Reference to price option'
                        ],
                        'option_id_back' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => false,
                            'description' => 'Reference to price option back if is roundtrip search'
                        ],
                    ]
                ],
                'clearSegments' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Booking/ClearSegments',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'search_option' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Reference to searching identifier search_option'
                        ],
                    ]
                ],
                'getTotalCost' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Booking/GetTotalCost',
                    'responseModel' => 'getResponse',
                ],
                'setCustomer' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Booking/SetCustomer',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'first_name' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'First Name'
                        ],
                        'last_name' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Last Name'
                        ],
                        'birth_date' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Birth date'
                        ],
                        'gender' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'example' => 'M or F'
                        ],
                        'title' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'example' => 'Mr, Mrs, Mss, Dr, etc.'
                        ],
                        'Passport' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'example' => 'A0000001'
                        ],
                        'PassportIssued' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'example' => '2014-01-10'
                        ],
                        'PassportExpire' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'example' => '2025-01-10'
                        ],
                        'PassportCountry' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'example' => 'MD'
                        ],
                        'Contact' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'Customer contact, phone',
                            'example' => '+37322888333'

                        ],
                        'Email' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'example' => 'ion.ciobanu@gmail.com'
                        ],
                        'Passenger_id' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => false,
                            'description' => 'Copy from passenger with item in passenger_id;
                                            in case when customer is between passengers, and are passengers are olready registered,
                                            this method can be called with passenger_id parameter only.
                                            It is not allowed a combination of passenger_id and other parameters'
                        ],
                    ]
                ],
                'setPassengers' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Booking/SetPassengers',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'passengers' => [
                            'type' => 'array',
                            'location' => 'query',
                            'required' => true,
                            'items' => [
                                [
                                    'passenger_id' => [
                                        'type' => 'string',
                                        'location' => 'query',
                                        'required' => true,
                                        'description' => 'Passenger id, starting from 0'
                                    ],
                                    'first_name' => [
                                        'type' => 'string',
                                        'location' => 'query',
                                        'required' => true,
                                        'description' => 'First Name'
                                    ],
                                    'last_name' => [
                                        'type' => 'string',
                                        'location' => 'query',
                                        'required' => true,
                                        'description' => 'Last Name'
                                    ],
                                    'birth_date' => [
                                        'type' => 'string',
                                        'location' => 'query',
                                        'required' => true,
                                        'description' => 'Birth date'
                                    ],
                                    'gender' => [
                                        'type' => 'string',
                                        'location' => 'query',
                                        'required' => true,
                                        'example' => 'M or F'
                                    ],
                                    'title' => [
                                        'type' => 'string',
                                        'location' => 'query',
                                        'required' => true,
                                        'example' => 'Mr, Mrs, Mss, Dr, etc.'
                                    ],
                                    'Passport' => [
                                        'type' => 'string',
                                        'location' => 'query',
                                        'required' => true,
                                        'example' => 'A0000001'
                                    ],
                                    'PassportIssued' => [
                                        'type' => 'string',
                                        'location' => 'query',
                                        'required' => true,
                                        'example' => '2014-01-10'
                                    ],
                                    'PassportExpire' => [
                                        'type' => 'string',
                                        'location' => 'query',
                                        'required' => true,
                                        'example' => '2025-01-10'
                                    ],
                                    'PassportCountry' => [
                                        'type' => 'string',
                                        'location' => 'query',
                                        'required' => true,
                                        'example' => 'MD'
                                    ],
                                    'Contact' => [
                                        'type' => 'string',
                                        'location' => 'query',
                                        'required' => true,
                                        'description' => 'Customer contact, phone',
                                        'example' => '+37322888333'

                                    ],
                                    'Email' => [
                                        'type' => 'string',
                                        'location' => 'query',
                                        'required' => true,
                                        'example' => 'ion.ciobanu@gmail.com'
                                    ],
                                ]
                            ]
                        ],
                    ]
                ],
                'setFormOfPayment' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Booking/SetFormOfPayment',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'form_of_payment' => [
                            'type' => 'string',
                            'location' => 'query',
                            'required' => true,
                            'description' => 'CA – cash, CC – credit card, IN – invoice',
                            'example' => 'CA'
                        ],
                    ]
                ],
                'getCurrentBooking' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Booking/GetCurrentBooking',
                    'responseModel' => 'getResponse',
                ],
                'finalizeBooking' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Booking/FinalizeBooking',
                    'responseModel' => 'getResponse',
                    'description' => 'Confirm reservation',
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