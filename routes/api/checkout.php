<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Webhooks\StripeWebhookController;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::prefix('checkout')->group(function () {
        Route::get('/', [CheckoutController::class, 'show']);
        Route::post('/', [CheckoutController::class, 'validate']);
        Route::post('/address', [CheckoutController::class, 'address']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{order}', [OrderController::class, 'show']);

    });

    Route::prefix('payment')->group(function () {
        Route::get('/show', [PaymentController::class, 'show']);
        Route::post('/intent', [PaymentController::class, 'store']);
        Route::post('/confirm', [PaymentController::class, 'confirm']);
    });
});

Route::prefix('webhooks')->group(function () {
    Route::post('/stripe', [StripeWebhookController::class, 'handle']);
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
*/
