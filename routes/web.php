<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LocationController;





Route::get('/', function () {
    return view('mainpage.home');
});

Route::get('/sample', function () {
    return view('query.locate');
});

Route::get('about', function () {
    return view('AboutUs.about');
});

Route::get('/', [QuoteController::class, 'countryDb'])->name('retrieveShipments');
Route::get('/getToken', [QuoteController::class, 'getToken'])->name('hello');
Route::get('/getQuote', [QuoteController::class, 'getQuote'])->name('retrieveShipments');

Route::post('/shipment', [QuoteController::class, 'shipping'])->name('shipPage');


Route::post('/createShipment', [QuoteController::class, 'createdShipment'])->name('createdShipment');



// Radar API


// Route::get('/search', [LocationController::class, 'search']);



// Payment API

Route::post('/payment', [PaymentController::class, 'processPayment'])->name('payment');
Route::get('/success', [PaymentController::class, 'success'])->name('success');
Route::get('/error', [PaymentController::class, 'error'])->name('error');


