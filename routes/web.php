<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripeController;

Route::get('/', function () {
    return view('stripe.payment');
});

Route::post('process-payment', [StripeController::class, 'processPayment']);


