@extends('layouts.layout')
@section('title', 'Payment | Success')
@section('css_content', 'css/style.css')

@section('content')
<div class="container-md mt-5 d-flex justify-content-center">
    <div class="card shadow-lg" style="width: 900px; border-radius: 15px;">
        <div class="card-header text-center bg-primary text-white" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
            <h4 class="mb-0 text-light">Payment Receipt</h4>
        </div>
        <div class="card-body">
            <h5 class="card-title">Tracking #: <span class="font-weight-bold">{{ session('trackingId') }}</span></h5>
            <h5 class="card-title">Transaction ID</h5>
            <p class="card-text text-muted">{{ $payment['payment_id'] }}</p>
            <h5 class="card-title">Payer ID</h5>
            <p class="card-text text-muted">{{ $payment['payer_id'] }}</p>
            <h5 class="card-title">Payer Email</h5>
            <p class="card-text text-muted">{{ $payment['payer_email'] }}</p>
            <h5 class="card-title">Amount</h5>
            <p class="card-text text-muted">{{ $payment['amount'] }} {{ $payment['currency'] }}</p>
            <h5 class="card-title">Status</h5>
            <p class="card-text text-muted">{{ ucfirst($payment['status']) }}</p>
            <div class="d-flex justify-content-center align-items-center">
                <a href="/" class="btn btn-primary btn-block">Return Home</a>
                <a href="{{ session('trackingUrl') }}" class="btn btn-outline-primary btn-md mx-2" target="_blank">View Receipt</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js_content', 'js/locatorRadar.js')
