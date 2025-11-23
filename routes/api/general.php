<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::prefix('categories')->group(function () {
    Route::get('', [CategoryController::class, 'index']);
    Route::get('/{slug}/products', [CategoryController::class, 'products']);
});

Route::prefix('products')->group(function () {
    Route::get('', [ProductController::class, 'top']);
    Route::get('/{product}', [ProductController::class, 'show']);
});
