<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Webhooks\StripeWebhookController;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::prefix('checkout')->group(function () {
        Route::get('/', [CheckoutController::class, 'show']);
        Route::post('/', [CheckoutController::class, 'validate']);
        Route::post('/address', [CheckoutController::class, 'address']);
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
