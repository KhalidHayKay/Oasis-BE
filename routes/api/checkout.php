<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::prefix('checkout')->group(function () {
        Route::get('/validate', [CheckoutController::class, 'validate']);
        Route::post('/customer', [CheckoutController::class, 'attachCustomer']);
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
*/

/*
Route::prefix('orders')->group(function () {
    Route::post('/', 'OrderController@store');
    Route::get('/{order}', 'OrderController@show');

    Route::post('/{order}/payments', 'OrderPaymentController@store');
});
*/

/*
Route::prefix('webhooks')->group(function () {
    Route::post('/payments', 'PaymentWebhookController@handle');
});

*/
