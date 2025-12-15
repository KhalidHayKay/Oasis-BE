<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::prefix('cart')->group(function () {
        Route::get('', [CartController::class, 'show']);
        Route::post('/items', [CartController::class, 'add']);
        Route::patch('/items/{product}/quantity/increment', [CartController::class, 'incrementQ']);
        Route::patch('/items/{product}/quantity/decrement', [CartController::class, 'decrementQ']);
        Route::delete('/items/{product}', [CartController::class, 'remove']);
        Route::delete('', [CartController::class, 'clear']);
    });
});
