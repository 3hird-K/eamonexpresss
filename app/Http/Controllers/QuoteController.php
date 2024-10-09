<?php

namespace App\Http\Controllers;

use App\Services\FedExService;
use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Support\Facades\Log;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use PhpParser\JsonDecoder;

class QuoteController extends Controller
{
    protected $fedExService;

    public function __construct(FedExService $fedExService)
    {
        $this->fedExService = $fedExService;
    }

    public function countryDb()
    {
        $countries = Country::all();
        return view('mainpage.home', compact('countries'));
    }

    public function getToken(Request $request)
    {
        // Fetch and display the OAuth token for debugging
        dd($this->fedExService->getAuthToken());
    }


    public function getQuote(Request $request)
    {
        // Retrieve form data
        $fromCountry = $request->input('fromCountry');
        $toCountry = $request->input('toCountry');
        $fromZip = $request->input('zipcodeFrom');
        $toZip = $request->input('zipcodeTo');
        $weight = $request->input('weight'); // Weight input from form

        // dd($fromCountry, $toCountry, $fromZip, $toZip, $weight);

        // Inputs to be passed on shipment view
        $recipientStreet = $request->input('recipientStreet');
        $recipientCity = $request->input('recipientCity');
        $recipientstateOrProvinceCode = $request->input('recipientstateOrProvinceCode');
        $shipperStreet = $request->input('shipperStreet');
        $shipperCity = $request->input('shipperCity');
        $shipperstateOrProvinceCode = $request->input('shipperstateOrProvinceCode');

        // Debugging: Dump and die to check the values
        // dd($recipientStreet, $recipientCity, $recipientstateOrProvinceCode, $shipperStreet, $shipperCity, $shipperstateOrProvinceCode);

        session([
            'recipientStreet' => $recipientStreet,
            'recipientCity' => $recipientCity,
            'recipientstateOrProvinceCode' => $recipientstateOrProvinceCode,
            'shipperStreet' => $shipperStreet,
            'shipperCity' => $shipperCity,
            'shipperstateOrProvinceCode' => $shipperstateOrProvinceCode,
        ]);




        // Validate form inputs
        $validatedData = $request->validate([
            'fromCountry' => 'required|string|max:2',   // Example: US, UK
            'toCountry'   => 'required|string|max:2',
            'zipcodeFrom' => 'required|string|max:10',
            'zipcodeTo'   => 'required|string|max:10',
            'weight'      => 'required|numeric|min:0.1', // Ensure weight is numeric and > 0
        ]);


        // dd($validatedData);

        try {
            // Call FedEx service to get rates
            $rates = $this->fedExService->getQuote(
                $fromCountry,
                $fromZip,
                $toCountry,
                $toZip,
                $weight
            );
            // dd($rates);


        session([
            'fromCountry' => $fromCountry,
            'toCountry' => $toCountry,
            'weight' => $weight,
            'zipcodeFrom' => $fromZip,
            'zipcodeTo' => $toZip,
        ]);


            return view('shipments.reqShipment', compact('rates', 'validatedData'));
        } catch (\Exception $e) {


            $error = json_decode($e->getMessage(),true);

            dd($error);

            if (json_last_error() === JSON_ERROR_NONE && isset($error['errors'][0]['code'])) {
                $jsonErr = $error['errors'][0]['code'];

                if ($jsonErr === "SERVICE.PACKAGECOMBINATION.INVALID") {
                    session()->flash('error', 'Invalid recipient postal code');
                } elseif ($jsonErr === "COUNTRY.POSTALCODE.INVALID") {
                    session()->flash('error', 'Invalid shipper postal code.');
                } else {
                    // Optional: Handle other error codes or provide a default message
                    session()->flash('error', 'An unknown error occurred.');
                }
            } else {
                // session()->flash('error', 'An error occurred while processing your request.');
            }

            // $jsonErr = $error['errors'][0]['code'];

            // if($jsonErr ===  "SERVICE.PACKAGECOMBINATION.INVALID"){

            //     session()->flash('error', 'Invalid recipient postal code');
            // } elseif ($jsonErr === "COUNTRY.POSTALCODE.INVALID" ){
            //     session()->flash('error', 'Invalid shipper postal code.');
            // }
            $countries = Country::all();



            // SERVICE.PACKAGECOMBINATION.INVALID - for recipient postal
            // COUNTRY.POSTALCODE.INVALID - for shipper postal
            return view('mainpage.home', compact('countries'));
                // return redirect()->back()->withErrors('Failed to retrieve shipping rates.');
        }

    }




    public function shipping(Request $request){

        $serviceType = $request->input(key: 'serviceType');
        $totalNetCharge = $request->input(key: 'totalNetCharge');
        // session()->forget('totalNetCharge');

        session([
            'serviceType' => $serviceType,
            'totalNetCharge' => $totalNetCharge
        ]);

    $data = [
        'fromCountry' => session('fromCountry'),
        'toCountry' => session('toCountry'),
        'weight' => session('weight'),
        'serviceType' => session('serviceType'),
        'totalNetCharge' => session('totalNetCharge'),
        'recipientStreet' => session('recipientStreet'),
        'recipientCity' => session('recipientCity'),
        'recipientstateOrProvinceCode' => session('recipientstateOrProvinceCode'),
        'shipperStreet' => session('shipperStreet'),
        'shipperCity' => session('shipperCity'),
        'shipperstateOrProvinceCode' => session('shipperstateOrProvinceCode'),
    ];


    return view('shipments.shipment', $data);


    }


    public function createdShipment(Request $request)
    {

        try {
           // Retrieve session values
         $shipperCountryCode = session('fromCountry');
         $recipientCountryCode = session('toCountry');
         $inWeight = session('weight');
         $weight = floatval($inWeight);
         $serviceType = session('serviceType');
         $fromZip = session('zipcodeFrom');
         $toZip = session('zipcodeTo');
         $totalNetCharge = session('totalNetCharge');


        //  dd($totalNetCharge, $weight);
        //  dd($serviceType);

            // dd($fromZip, $toZip);

            // Validate request data
            $validatedData = $request->validate([
                'shipperName' => 'required|string|max:255',
                'shipperPhone' => 'required|numeric',
                'shipperStreet' => 'required|string|max:35',
                'shipperCity' => 'required|string|max:100',
                'recipientName' => 'required|string|max:255',
                'recipientPhone' => 'required|numeric',
                'recipientStreet' => 'required|string|max:35',
                'recipientCity' => 'required|string|max:100',
                'shipDate' => 'required|date',
                'packagingType' => 'required|string',
                'pickupType' => 'required|string',
                'imageType' => 'required|string',
                'labelStockType' => 'required|string',
                'labelResponseOptions' => 'required|string',
                'shipperstateOrProvinceCode' => 'required|string|max:2',
                'recipientstateOrProvinceCode' => 'required|string|max:2',
                'customsValueAmount' => 'required',
                'customsValueQuantity' => 'required'
            ]);
        //    dd($validatedData);

            // Store session data
            session([
                'fromCountry' => $shipperCountryCode,
                'toCountry' => $recipientCountryCode,
                'weight' => $weight,
                       ]);


            // dd($recipientCountryCode);
            // dd($toZip);
            // Call FedEx service to get rates

            //  dd($validatedData['recipientCity'],$validatedData['recipientstateOrProvinceCode'],$toZip,$recipientCountryCode);
            $shipRequest = $this->fedExService->BookNow(
                $validatedData['shipperName'],
                $validatedData['shipperPhone'],
                $validatedData['shipperStreet'],
                $validatedData['shipperCity'],
                $shipperCountryCode,
                $validatedData['recipientName'],
                $validatedData['recipientPhone'],
                $validatedData['recipientStreet'],
                $validatedData['recipientCity'],
                $recipientCountryCode,
                $validatedData['shipDate'],
                $validatedData['packagingType'],
                $validatedData['pickupType'],
                $validatedData['imageType'],
                $validatedData['labelStockType'],
                $validatedData['labelResponseOptions'],
                $weight,
                $serviceType,
                $fromZip,
                $toZip,
                $validatedData['shipperstateOrProvinceCode'],
                $validatedData['recipientstateOrProvinceCode'],
                $totalNetCharge,
                $validatedData['customsValueAmount'],
                $validatedData['customsValueQuantity'],

            );


            //  dd($shipRequest);
            // dd($shipRequest);

            $qty = $validatedData['customsValueQuantity'];

            $totalWithPackage = strval($totalNetCharge * $qty);
            // dd($totalWithPackage);




            $value = session('reqErrorResponse');

            if($value =="test"){
            return redirect()->back()->withInput()->withErrors("Error");

            }else{
                session([
                    'trackingId' => $shipRequest['output']['transactionShipments'][0]['shipmentDocuments'][0]['trackingNumber'],
                    'trackingUrl' => $shipRequest['output']['transactionShipments'][0]['shipmentDocuments'][0]['url'],
                    'serviceTyped' => $shipRequest['output']['transactionShipments'][0]['serviceType'],
                ]);



                // return view('shipments.createdShipment', compact('totalWithPackage'));


                $paymentData = [
                    'payment_id' => session('payment_id'),
                    'payer_id' => session('payer_id'),
                    'payer_email' => session('payer_email'),
                    'amount' => session('amount'),
                    'currency' => session('currency'),
                    'status' => session('status'),
                ];


                // dd($paymentData);

                return view('paypal.success', [
                    'payment' => $paymentData, // Pass payment data to the view
                ]);
            }





        } catch (\Exception $e) {
            Log::error('Error fetching FedEx rates: ' . $e->getMessage());
            dd($e->getMessage());
            return redirect()->back()->withInput()->withErrors('Invalid Credentials',$e->getMessage() );

        }

    }



}
