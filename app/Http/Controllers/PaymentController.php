<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Omnipay\Omnipay;
use App\Models\Payment;


class PaymentController extends Controller
{
    private $gateway;

    public function __construct()
    {
        // Initialize the PayPal gateway
        $this->gateway = Omnipay::create("PayPal_Rest");
        $this->gateway->setClientId(env("PAYPAL_CLIENT_ID"));
        $this->gateway->setSecret(env("PAYPAL_CLIENT_SECRET"));
        $this->gateway->setTestMode(true); // Set to false for live mode
    }

    public function processPayment(Request $request)
    {
        // $amount = $request->input("totalNetCharge");

        $amount = number_format($request->input("totalNetCharge"), 2, '.', '');

        session(['totalNetCharge' => $amount,
                'serviceType' => $request->input('serviceType'),
    ]);

        try {
            // Create a payment request
            $response = $this->gateway->purchase([
                'amount' => $amount,
                'currency' => env('PAYPAL_CURRENCY'),
                'returnUrl' => url('success'),
                'cancelUrl' => url('error'),
            ])->send();

            // dd($response);


        if ($response->isRedirect()) {
            // redirect to offsite payment gateway
            $response->redirect();
        } elseif ($response->isSuccessful()) {
            // payment was successful: update database
            print_r($response);
        } else {
            // payment failed: display message to customer
            echo $response->getMessage();
        }

        } catch(\Throwable $th){
            return $th->getMessage();
        }

    }

    public function success(Request $request)
{
    if ($request->input('paymentId') && $request->input('PayerID')) {
        $purchaseRequest = $this->gateway->completePurchase(array(
            'payer_id' => $request->input('PayerID'),
            'transactionReference' => $request->input('paymentId'),
        ));

        $response = $purchaseRequest->send();

        if ($response->isSuccessful()) {
            $arr = $response->getData();

            $payment = new Payment();
            $payment->payment_id = $arr['id'];
            $payment->payer_id = $arr['payer']['payer_info']['payer_id'];
            $payment->payer_email = $arr['payer']['payer_info']['email'];
            $payment->amount = $arr['transactions'][0]['amount']['total'];
            $payment->currency = env('PAYPAL_CURRENCY');
            $payment->status = $arr['state'];

            $payment->save();


            session([
                'payment_id' => $payment->payment_id,
                'payer_id' => $payment->payer_id,
                'payer_email' => $payment->payer_email,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'status' => $payment->status,

            ]);

            // $pid = session("payment_id");
            // dd($pid);


            // return view('paypal.success', [
            //     'payment' => $payment, // Pass payment data to the view
            // ]);

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

        } else {
            return redirect()->route('error')->with('error', $response->getMessage());
        }
    } else {
        return redirect()->route('error')->with('error', 'Payment is declined!');
    }
}





    public function error(){
        return view('paypal.error');
    }

}
