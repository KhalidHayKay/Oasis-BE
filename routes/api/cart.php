<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::prefix('cart')->group(function () {
        Route::get('', [CartController::class, 'show']);
        Route::post('/items', [CartController::class, 'add']);
        Route::post('/sync', [CartController::class, 'sync']);
        Route::patch('/items/{item}/quantity/increment', [CartController::class, 'incrementQ']);
        Route::patch('/items/{item}/quantity/decrement', [CartController::class, 'decrementQ']);
        Route::delete('/items/{item}', [CartController::class, 'remove']);
        Route::delete('', [CartController::class, 'clear']);
    });
});
