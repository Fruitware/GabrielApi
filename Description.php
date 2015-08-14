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
//            'baseUrl' => 'https://b2bportal.demo.qsystems.md/', // demo server
//            'baseUrl' => 'https://b2b.airmoldova.md/', // production server
            'operations' => [
                'login' => [
                    'httpMethod' => 'POST',
                    'uri' => '/GabrielAPI/Account/GetTicket',
                    'description' => 'Authenticate and obtain a work session in B2B Portal system.',
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
                        ]
                    ]
                ],
                'reset' => [
                    'httpMethod' => 'POST',
                    'uri' => '/GabrielAPI/Account/FlushSession',
                    'description' => 'Start new booking session (Session Flush) - resets all data previously recorded in the number of session',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                        ]
                    ]
                ],
                'getSupportedLanguages' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Settings/GetSupportedLanguages',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
                    ]
                ],
                'changeLanguage' => [
                    'httpMethod' => 'POST',
                    'uri' => '/GabrielAPI/Settings/ChangeTheSessionLanguage',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'culture_code' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Language culture code',
                            'example' => 'ru-RU'
                        ],
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
                    ]
                ],
                'getCountryName' => [
                    'httpMethod' => 'POST',
                    'uri' => '/GabrielAPI/Info/GetCountryName',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'country' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Partial name or ISO code of searching country',
                            'example' => 'MD or MOL'
                        ],
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
                    ]
                ],
                'getCityName' => [
                    'httpMethod' => 'POST',
                    'uri' => '/GabrielAPI/Info/GetCityName',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'city' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Partial name or ISO code of searching city',
                            'example' => 'KIV or CHI'
                        ],
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
                    ]
                ],
                'getAirlineName' => [
                    'httpMethod' => 'POST',
                    'uri' => '/GabrielAPI/Info/GetAirlineName',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'airline' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Partial name or IATA code of searching airline',
                            'example' => '9U'
                        ],
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
                    ]
                ],
                'getBestPrice' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Info/GetBestPrice',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
                    ]
                ],
                'getCalendarShopping' => [
                    'httpMethod' => 'POST',
                    'uri' => '/GabrielAPI/Info/GetCalendarShopping',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'cityfrom' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Departure airport/city',
                        ],
                        'cityto' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Arrival airport/city',
                        ],
                        'datefrom' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                            'description' => 'Starting searching date',
                        ],
                        'dateto' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                            'description' => 'Ending searching date',
                        ],
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
                    ]
                ],
                'getCurrencyExchange' => [
                    'httpMethod' => 'POST',
                    'uri' => '/GabrielAPI/Info/GetCurrencyExchange',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'fromcurr' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'From currency',
                            'example' => 'EUR',
                        ],
                        'tocurr' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'To currency',
                            'example' => 'MLD',
                        ],
                        'amount' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Amount to convert',
                            'example' => '100.01',
                        ],
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
                    ]
                ],
                'getDefaultSearchSettings' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Booking/GetDefaultSearchSettings',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
                    ]
                ],
                'setNumberOfPassengers' => [
                    'httpMethod' => 'POST',
                    'uri' => '/GabrielAPI/Booking/SetNumberOfPassengers',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'seats' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Passengers (total seats, including children)',
                        ],
                        'children' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Number of children (2 -12 years)',
                        ],
                        'infants' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Number of infants (under 2 years)',
                        ],
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
                    ]
                ],
                'getSegments' => [
                    'httpMethod' => 'POST',
                    'uri' => '/GabrielAPI/Booking/GetSegments',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'airport_from' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Departure airport/city',
                            'example' => 'KIV',
                        ],
                        'airport_to' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Arrival airport/city',
                            'example' => 'MOW',
                        ],
                        'dep_date' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Departure date'
                        ],
                        'ret_date' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                            'description' => 'Return date'
                        ],
                        'search_option' => [
                            'type' => 'string',
                            'location' => 'json',
                            'default' => 1,
                            'required' => true,
                            'description' => 'Search identifier – ability to have more than one active searches'
                        ],
                        'direct_search' => [
                            'type' => 'boolean',
                            'location' => 'json',
                            'default' => false,
                            'required' => false,
                            'description' => 'Ignore cache of available segments. Default is false'
                        ],
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
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
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
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
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
                    ]
                ],
                'setSegment' => [
                    'httpMethod' => 'POST',
                    'uri' => '/GabrielAPI/Booking/SetSegment',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'search_option' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Reference to searching identifier search_option'
                        ],
                        'option_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Reference to price option'
                        ],
                        'option_id_back' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                            'description' => 'Reference to price option back if is roundtrip search'
                        ],
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
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
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
                    ]
                ],
                'getTotalCost' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Booking/GetTotalCost',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
                    ]
                ],
                'setPassengers' => [
                    'httpMethod' => 'POST',
                    'uri' => '/GabrielAPI/Booking/SetPassengers',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ],
                        'passengers' => [
                            'type' => 'array',
                            'location' => 'json',
                            'required' => true,
                            'items' => [
                                [
                                    'passenger_id' => [
                                        'type' => 'integer',
                                        'location' => 'json',
                                        'required' => true,
                                        'description' => 'Passenger id, starting from 0'
                                    ],
                                    'first_name' => [
                                        'type' => 'string',
                                        'location' => 'json',
                                        'required' => true,
                                        'description' => 'First Name'
                                    ],
                                    'last_name' => [
                                        'type' => 'string',
                                        'location' => 'json',
                                        'required' => true,
                                        'description' => 'Last Name'
                                    ],
                                    'birth_date' => [
                                        'type' => 'string',
                                        'location' => 'json',
                                        'required' => true,
                                        'description' => 'Birth date'
                                    ],
                                    'gender' => [
                                        'type' => 'string',
                                        'location' => 'json',
                                        'required' => true,
                                        'example' => 'M or F'
                                    ],
                                    'title' => [
                                        'type' => 'string',
                                        'location' => 'json',
                                        'required' => true,
                                        'example' => 'Mr, Mrs, Mss, Dr, etc.'
                                    ],
                                    'Passport' => [
                                        'type' => 'string',
                                        'location' => 'json',
                                        'required' => true,
                                        'example' => 'A0000001'
                                    ],
                                    'PassportIssued' => [
                                        'type' => 'string',
                                        'location' => 'json',
                                        'required' => true,
                                        'example' => '2014-01-10'
                                    ],
                                    'PassportExpire' => [
                                        'type' => 'string',
                                        'location' => 'json',
                                        'required' => false,
                                        'example' => '2025-01-10'
                                    ],
                                    'PassportCountry' => [
                                        'type' => 'string',
                                        'location' => 'json',
                                        'required' => true,
                                        'example' => 'MD'
                                    ],
                                    'Contact' => [
                                        'type' => 'string',
                                        'location' => 'json',
                                        'required' => false,
                                        'description' => 'Customer contact phone',
                                        'example' => '+37322888333'

                                    ],
                                    'Email' => [
                                        'type' => 'string',
                                        'location' => 'json',
                                        'required' => false,
                                        'example' => 'ion.ciobanu@gmail.com'
                                    ],
                                ]
                            ]
                        ],
                    ]
                ],
                'setCustomer' => [
                    'httpMethod' => 'POST',
                    'uri' => '/GabrielAPI/Booking/SetCustomer',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'first_name' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'First Name'
                        ],
                        'last_name' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Last Name'
                        ],
                        'birth_date' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Birth date'
                        ],
                        'gender' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'example' => 'M or F'
                        ],
                        'title' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'example' => 'Mr, Mrs, Mss, Dr, etc.'
                        ],
                        'Passport' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'example' => 'A0000001'
                        ],
                        'PassportIssued' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'example' => '2014-01-10'
                        ],
                        'PassportExpire' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'example' => '2025-01-10'
                        ],
                        'PassportCountry' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'example' => 'MD'
                        ],
                        'Contact' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Customer contact, phone',
                            'example' => '+37322888333'
                        ],
                        'Email' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'example' => 'ion.ciobanu@gmail.com'
                        ],
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
                    ]
                ],
                'setPassengerAsCustomer' => [
                    'httpMethod' => 'POST',
                    'uri' => '/GabrielAPI/Booking/SetCustomer',
                    'responseModel' => 'getResponse',
                    'description' => 'One of the passengers can be the customer, if you already call setPassengers method',
                    'parameters' => [
                        'passenger_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'Copy from passenger with item in passenger_id;
                                            in case when customer is between passengers, and passengers are already registered,
                                            this method can be called with Passenger_id parameter only.
                                            It is not allowed a combination of Passenger_id and other parameters'
                        ],
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
                    ]
                ],
                'setFormOfPayment' => [
                    'httpMethod' => 'POST',
                    'uri' => '/GabrielAPI/Booking/SetFormOfPayment',
                    'responseModel' => 'getResponse',
                    'parameters' => [
                        'form_of_payment' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => true,
                            'description' => 'CA – cash, CC – credit card, IN – invoice',
                            'example' => 'CA'
                        ],
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
                    ]
                ],
                'getCurrentBooking' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Booking/GetCurrentBooking',
                    'responseModel' => 'getResponse',
                    'description' => 'Get current booking info (segments, customer, passengers, total cost)',
                    'parameters' => [
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
                    ]
                ],
                'finalizeBooking' => [
                    'httpMethod' => 'GET',
                    'uri' => '/GabrielAPI/Booking/FinalizeBooking',
                    'responseModel' => 'getResponse',
                    'description' => 'Confirm reservation',
                    'parameters' => [
                        'session_id' => [
                            'type' => 'string',
                            'location' => 'json',
                            'required' => false,
                        ]
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