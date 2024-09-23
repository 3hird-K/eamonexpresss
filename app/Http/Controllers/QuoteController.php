<?php

namespace App\Http\Controllers;

use App\Services\FedExService;
use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Support\Facades\Log;

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


        session([
            'fromCountry' => $fromCountry,
            'toCountry' => $toCountry,
            'weight' => $weight,
            'zipcodeFrom' => $fromZip,
            'zipcodeTo' => $toZip,
        ]);
        

            return view('shipments.reqShipment', compact('rates', 'validatedData'));
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error fetching FedEx rates: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->withErrors('Failed to retrieve shipping rates.');
        }

    //     return redirect()->back()->withErrors('');
    }

    public function shipPage(Request $request){
        $fromCountry = session('fromCountry');
        $toCountry = session('toCountry');
        $weight = session('weight');
        $serviceType = $request->input(key: 'serviceType');

        session([
            'serviceType' => $serviceType,
        ]);

        return view('shipments.shipment', compact('fromCountry', 'toCountry', 'weight', 'serviceType'));
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
                'recipientstateOrProvinceCode' => 'required|string|max:2'
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
            );
           
            dd($shipRequest);
         

     
            $value = session('reqErrorResponse');
           
                if($value =="test"){
                  
                // $data = json_decode($shipRequest, true);
                // // dd($data);
                // $errorCodes = array_map(function($error) {
                //     return $error['code'];
                // }, $data['errors']);

                 
                // $errorHandler= '';
                // if($errorCodes[0] == "PHONENUMBER.TOO.LONG"){
                //     $errorHandler = "Phone Number is wrong!";
                // }else if($errorCodes[0] == "RECIPIENT.STATEORPROVINCECODE.INVALID"){
                //     $errorHandler = "Recipient Province Code is wrong!";
                // }
                return redirect()->back()->withInput()->withErrors("Error");
                  
                }else{
                    //  dd($shipRequest);
                    return view('shipments.createdShipment');
                }
              
                if($errorDetails[0]['name'] == "errors"){
                    return redirect()->back()->withInput()->withErrors('Invalid Credentials');
                }else{
                    return view('shipments.createdShipment');
                }











                // dd($shipRequest);
            // if($errorDetails[0]['name'] == "errors"){
            //     // return view('shipments.shipment', compact('serviceType','fromCountry','toCountry'));
            //     return redirect()->back()->withInput()->withErrors('Invalid Credentials');
            // }else{
            //     return view('shipments.createdShipment');
            // }
            // $encodedLabel = $shipRequest['output']['transactionShipments'][0]['pieceResponses'][0]['packageDocuments'][0]['url'];

            // dump($shipRequest);
            // $errorHand = $shipRequest['data']['reqResponse']['errors']['code'];
            // dd($shipRequest);

            // $errorHandler="";
            // $errorHandlers = 
            // session()->has('reqErrorResponse') ? session()->forget('') : session()->put('', $errorHandler);
            
            // if ($errorHandlers == null) {
            //     $jsonArray = json_decode($errorHandlers, true); // Use `true` for associative array
            //     $errorCode = $jsonArray['errors'][0]['code'] ?? null;
            //     return view('shipments.shipment', compact('errorCode', 'serviceType'));
            // } else {
            //     return view('shipments.createdShipment');
            // }
            
            // return view('shipments.createdShipment');
    
        } catch (\Exception $e) {
            Log::error('Error fetching FedEx rates: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors('Invalid Credentials', dd($e->getMessage()));
            
        }
       
    }


    
  
   

    

}
