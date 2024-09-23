<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache; // Use Cache for token storage
use Illuminate\Support\Facades\Log;

class FedExServices
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
        $this->isSandbox = env('FEDEX_SANDBOX') === 'true';
    }

    public function getAuthToken()
    {
        $endpoint = $this->isSandbox
            ?
            'https://apis.fedex.com/oauth/token'
            : 'https://apis-sandbox.fedex.com/oauth/token';

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
            throw new \Exception('Failed to retrieve FedEx OAuth token: ' . $response->body());
        }
    }


    public function getRates($fromCountry, $fromZip, $toCountry, $toZip, $weight)
{
    // Determine the API endpoint based on the environment
    $endpoint = $this->isSandbox
    ? 'https://apis.fedex.com/availability/v1/packageandserviceoptions'
    : 'https://apis-sandbox.fedex.com/availability/v1/packageandserviceoptions';


    // Retrieve the authentication token
    $authToken = $this->getAuthToken();
    if (!$authToken) {
        throw new \Exception('Failed to retrieve access token.');
    }

    // Make the API request
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $authToken,
        'x-locale' => 'en_US',
        'x-customer-transaction-id' => $this->key
    ])->post($endpoint, [
        "requestedShipment" => [
            "shipper" => [
                "address" => [
                    "postalCode" => $fromZip,
                    "countryCode" => $fromCountry
                ]
            ],
            "recipients" => [
                [
                    "address" => [
                        "postalCode" => $toZip,
                        "countryCode" => $toCountry
                    ]
                ]
            ],
            "packageDetails" => [
                [
                    "weight" => [
                        "units" => "LB", // or "KG" depending on your needs
                        "value" => $weight
                    ]
                ]
            ]
        ],
        "carrierCodes" => [
            "FDXE",
            "FDXG"
        ]
    ]);

    // Handle the response
    if ($response->successful()) {
        return $response->json();
    } else {
        Log::error('FedEx Rate Request Failed', [
            'endpoint' => $endpoint,
            'headers' => $response->headers(),
            'body' => $response->json(),
            'response' => $response->body(),
            'status' => $response->status(),
        ]);

        throw new \Exception('Failed to retrieve shipping rates: ' . $response->body());
    }
}





}
