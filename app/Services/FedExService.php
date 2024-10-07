<?php
namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class FedExService
{
    protected $key;
    protected $password;
    protected $accountNumber;
    protected $meterNumber;
    protected $isSandbox;

    public function __construct()
    {
        $this->key = env('FEDEX_KEY');
        $this->password = env('FEDEX_PASSWORD');
        $this->accountNumber = env('FEDEX_ACCOUNT_NUMBER');
        $this->meterNumber = env('FEDEX_METER_NUMBER');
        $this->isSandbox = env('FEDEX_SANDBOX');
    }

    /**
     * Retrieves the OAuth token from FedEx and caches it for one hour.
     *
     * @return string
     * @throws Exception
     */
    public function getAuthToken()
    {
        $endpoint = $this->isSandbox
            ? 'https://apis-sandbox.fedex.com/oauth/token'
            : 'https://apis.fedex.com/oauth/token';

        $response = Http::asForm()->post($endpoint, [
            'grant_type'    => 'client_credentials',
            'client_id'     => $this->key,
            'client_secret' => $this->password,
        ]);

        if ($response->successful()) {
            $tokenData = $response->json();
            $accessToken = $tokenData['access_token'];

            // Store the token in the cache (expires in 1 hour)
            Cache::put('fedex_access_token', $accessToken, now()->addHour());

            return $accessToken;
        } else {
            throw new Exception('Failed to retrieve FedEx OAuth token: ' . $response->body());
        }
    }

    /**
     * Retrieves rate and transit information for a shipment.
     *
     * @param string $fromCountry
     * @param string $fromZip
     * @param string $toCountry
     * @param string $toZip
     * @param float $weight
     * @return mixed
     * @throws Exception
     */
    public function getQuote($fromCountry, $fromZip, $toCountry, $toZip, $weight)
    {
        $endpoint = $this->isSandbox
            ? 'https://apis-sandbox.fedex.com/rate/v1/rates/quotes'
            : 'https://apis.fedex.com/rate/v1/rates/quotes';

        $authToken = $this->getAuthToken();

        if (!$authToken) {
            throw new Exception("Failed to retrieve access token.");
        }

        // Define the shipment data
        $shipmentData = [
            "accountNumber" => [
                "value" => $this->accountNumber
            ],
            "requestedShipment" => [
                "shipper" => [
                    "address" => [
                        "postalCode" => $fromZip,
                        "countryCode" => $fromCountry,
                    ]
                ],
                "recipient" => [
                    "address" => [
                        "postalCode" => $toZip,
                        "countryCode" => $toCountry,
                    ]
                ],
                "shipDateStamp" => now()->toDateString(),
                "pickupType" => "DROPOFF_AT_FEDEX_LOCATION",
                // "pickupType" => ["DROPOFF_AT_FEDEX_LOCATION","CONTACT_FEDEX_TO_SCHEDULE","USE_SCHEDULED_PICKUP"],
                "rateRequestType" => [
                    "LIST",
                    "ACCOUNT"
                ],
                "customsClearanceDetail" => [
                    "dutiesPayment" => [
                        "paymentType" => "SENDER",
                        "payor" => [
                            "responsibleParty" => null
                        ]
                    ],
                    "commodities" => [
                        [
                            "description" => "Camera",
                            "quantity" => 1,
                            "quantityUnits" => "PCS",
                            "weight" => [
                                "units" => "LB",
                                "value" => $weight
                            ],
                            "customsValue" => [
                                "amount" => 100,
                                "currency" => "USD"
                            ]
                        ]
                    ]
                ],
                "requestedPackageLineItems" => [
                    [
                        "weight" => [
                            "units" => "LB",
                            "value" => $weight
                        ]
                    ]
                ]
            ]
        ];

        // Make the API request
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $authToken,
        ])->post($endpoint, $shipmentData);

        if ($response->successful()) {
            return $response->json();
        } else {
            // throw new Exception('Failed to retrieve FedEx rate quote: ' . $response->body());
            throw new Exception($response->body());
        }
    }



    // public function BookNow($personName, $phoneNo,$streetLines,$city,$stateOrProvinceCode,$postalCode,$countryCode,$shipDatestamp,$serviceType,$packagingType,$pickupType,$labelStockType,$imageType,$weight){
    public function BookNow(
        $shipperName,
        $shipperPhone,
        $shipperStreet,
        $shipperCity,
        $shipperCountryCode,
        $recipientName,
        $recipientPhone,
        $recipientStreet,
        $recipientCity,
        $recipientCountryCode,
        $shipDate,
        $packagingType,
        $pickupType,
        $imageType,
        $labelStockType,
        $labelResponseOptions,
        $weight,
        $serviceType,
        $fromZip,
        $toZip,
        $shipperstateOrProvinceCode,
        $recipientstateOrProvinceCode,
        $totalNetCharge,
        $customAmount,
        $customQty
    ){

       

        $endpoint = $this->isSandbox
            ? 'https://apis-sandbox.fedex.com/ship/v1/shipments'
            : 'https://apis.fedex.com/ship/v1/shipments';

        $authToken = $this->getAuthToken();

        if (!$authToken) {
            throw new Exception("Failed to retrieve access token.");
        }
       
        

        // $bookingData = [
        //     "labelResponseOptions"=> $labelResponseOptions,
        //     "requestedShipment"=> [
        //       "shipper"=> [
        //         "contact"=> [
        //           "personName"=> $shipperName,
        //           "phoneNumber"=> $shipperPhone,
        //         //   "companyName"=> "Shipper Company Name"
        //         ],
        //         "address"=> [
        //           "streetLines"=> [
        //             $shipperStreet
        //           ],
        //           "city"=> $shipperCity,
        //           "stateOrProvinceCode"=> $shipperstateOrProvinceCode,
        //           "postalCode"=> $fromZip,
        //           "countryCode"=> $shipperCountryCode
        //         ]
        //       ],
        //       "recipients"=> [
        //         [
        //           "contact"=> [
        //             "personName"=> $recipientName,
        //             "phoneNumber"=> $recipientPhone,
        //             // "companyName"=> "Recipient Company Name"
        //           ],
        //           "address"=> [
        //             "streetLines"=> [
        //               $recipientStreet,
        //             ],
        //             "city"=> $recipientCity,
        //             "stateOrProvinceCode"=> $recipientstateOrProvinceCode,
        //             "postalCode"=> $toZip,
        //             "countryCode"=> $recipientCountryCode
        //           ]
        //         ]
        //       ],
        //       "shipDatestamp"=> $shipDate,
        //       "serviceType"=> $serviceType,
        //       "packagingType"=> $packagingType, // tobemodified
        //       "pickupType"=> $pickupType,
        //       "blockInsightVisibility"=> false,
        //       "shippingChargesPayment"=> [
        //         "paymentType"=> "SENDER"
        //       ],
        //       "labelSpecification"=> [
        //         "imageType"=> $imageType,
        //         "labelStockType"=> $labelStockType
        //       ],
        //       "customsClearanceDetail"=> [
        //         "dutiesPayment"=> [
        //           "paymentType"=> "SENDER"
        //         ],
        //         "isDocumentOnly"=> true,
        //         "commodities"=> [
        //           [
        //             "description"=> "Commodity description",
        //             "countryOfManufacture"=> "US",
        //             "quantity"=> $customQty,
        //             "quantityUnits"=> "PCS",
        //             "unitPrice"=> [
        //               "amount"=> $totalNetCharge,
        //               "currency"=> "USD"
        //             ],
        //             "customsValue"=> [
        //               "amount"=> $customAmount,
        //               "currency"=> "USD"
        //             ],
        //             "weight"=> [
        //               "units"=> "LB",
        //               "value"=> $weight
        //             ]
        //           ]
        //         ]
        //       ],
        //       "shippingDocumentSpecification"=> [
        //         "shippingDocumentTypes"=> [
        //           "COMMERCIAL_INVOICE"
        //         ],
        //         "commercialInvoiceDetail"=> [
        //           "documentFormat"=> [
        //             "stockType"=> "PAPER_LETTER",
        //             "docType"=> "PDF" // fixed to PDF
        //           ]
        //         ]
        //       ],
        //       "requestedPackageLineItems"=> [
        //         [
        //           "weight"=> [
        //             "units"=> "LB",
        //             "value"=> 70
        //           ]
        //         ]
        //       ]
        //     ],
        //     "accountNumber"=> [
        //       "value"=> "740561073"
        //     ]
        //     ];

        $bookingData = [
          "labelResponseOptions"=> "URL_ONLY",
          "requestedShipment"=> [
            "shipper"=> [
              "contact"=> [
                "personName"=> "SHIPPER NAME",
                "phoneNumber"=> 1234567890,
              //   "companyName"=> "Shipper Company Name"
              ],
              "address"=> [
                "streetLines"=> [
                  "SHIPPER STREET LINE 1"
                ],
                "city"=> "Memphis",
                "stateOrProvinceCode"=> "TN",
                "postalCode"=> 38116,
                "countryCode"=> "US"
              ]
            ],
            "recipients"=> [
              [
                "contact"=> [
                  "personName"=>"RECIPIENT NAME",
                  "phoneNumber"=> 1234567890,
                  // "companyName"=> "Recipient Company Name"
                ],
                "address"=> [
                  "streetLines"=> [
                    "RECIPIENT STREET LINE 1",
                    "RECIPIENT STREET LINE 2",
                    "RECIPIENT STREET LINE 3"
                  ],
                  "city"=> "RICHMOND",
                  "stateOrProvinceCode"=> "BC",
                  "postalCode"=> "V7C4V7",
                  "countryCode"=> "CA"
                ]
              ]
            ],
            "shipDatestamp"=> "2020-07-03",
            "serviceType"=> "INTERNATIONAL_PRIORITY",
            "packagingType"=> "YOUR_PACKAGING",// tobemodified
            "pickupType"=>  "USE_SCHEDULED_PICKUP",
            "blockInsightVisibility"=> false,
            "shippingChargesPayment"=> [
              "paymentType"=> "SENDER"
            ],
            "labelSpecification"=> [
              "imageType"=>  "PDF",
              "labelStockType"=> "PAPER_85X11_TOP_HALF_LABEL"
            ],
            "customsClearanceDetail"=> [
              "dutiesPayment"=> [
                "paymentType"=> "SENDER"
              ],
              "isDocumentOnly"=> true,
              "commodities"=> [
                [
                  "description"=> "Commodity description",
                  "countryOfManufacture"=> "US",
                  "quantity"=>1,
                  "quantityUnits"=> "PCS",
                  "unitPrice"=> [
                    "amount"=> 100,
                    "currency"=> "USD"
                  ],
                  "customsValue"=> [
                    "amount"=> 200,
                    "currency"=> "USD"
                  ],
                  "weight"=> [
                    "units"=> "LB",
                    "value"=> 20
                  ]
                ]
              ]
            ],
            "shippingDocumentSpecification"=> [
              "shippingDocumentTypes"=> [
                "COMMERCIAL_INVOICE"
              ],
              "commercialInvoiceDetail"=> [
                "documentFormat"=> [
                  "stockType"=> "PAPER_LETTER",
                  "docType"=> "PDF" // fixed to PDF
                ]
              ]
            ],
            "requestedPackageLineItems"=> [
              [
                "weight"=> [
                  "units"=> "LB",
                  "value"=> 70
                ]
              ]
            ]
          ],
          "accountNumber"=> [
            "value"=> "740561073"
          ]
          ];







            session()->forget('reqErrorResponse');
            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $authToken,
                ])->post($endpoint, $bookingData);

                if ($response->successful()) {
                    return $response->json();
                } else {
                    $reqResponse = $response->body();
                    session(['reqErrorResponse' => "test"]);
                    // $errorHand = $shipRequest['data']['reqResponse']['errors']['code'];


                    $data = [
                        'name' => 'errors',
                        'email' => compact('reqResponse'),
                    ];
                    return  $reqResponse;

                }
            } catch (Exception $e) {
                // Handle any exceptions thrown here
                return view('sample', ['errorMessage' => $e->getMessage()]);
            }




        // Make the API request
        // $response = Http::withHeaders([
        //     'Content-Type' => 'application/json',
        //     'Authorization' => 'Bearer ' . $authToken,
        // ])->post($endpoint, $bookingData);

        // if ($response->successful()) {
        //     return $response->json();
        // } else {
        //      $reqResponse = $response->body();
        //     session(['reqErrorResponse', $reqResponse]);
        //     throw new Exception('Failed to retrieve FedEx rate quote: ' . $response->body());
        // }


    }
}
