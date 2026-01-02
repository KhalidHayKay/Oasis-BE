<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::prefix('checkout')->group(function () {
        Route::get('/validate', [CheckoutController::class, 'validate']);
        Route::post('/customer', [CheckoutController::class, 'attachCustomer']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{order}', [OrderController::class, 'show']);
        // Route::post('/{order}/payments', [OrderPaymentController::class, 'store']);
    });
});

/*
Route::prefix('checkout')->group(function () {
    Route::post('/', 'CheckoutController@start');
    Route::get('/{checkout}', 'CheckoutController@show');

    Route::post('/{checkout}/customer', 'CheckoutCustomerController@store');
    Route::post('/{checkout}/addresses', 'CheckoutAddressController@store');

    Route::get('/{checkout}/shipping-options', 'CheckoutShippingOptionController@index');
    Route::post('/{checkout}/shipping', 'CheckoutShippingController@store');

    Route::post('/{checkout}/discounts', 'CheckoutDiscountController@store');
    Route::delete('/{checkout}/discounts/{code}', 'CheckoutDiscountController@destroy');
});

/*
Route::prefix('webhooks')->group(function () {
    Route::post('/payments', 'PaymentWebhookController@handle');
});

*/
