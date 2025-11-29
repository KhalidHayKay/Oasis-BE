<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InspirationController;

Route::prefix('categories')->group(function () {
    Route::get('', [CategoryController::class, 'index']);
    Route::get('/{slug}/products', [CategoryController::class, 'products']);
});

Route::prefix('products')->group(function () {
    Route::get('top', [ProductController::class, 'top']);
    Route::get('/{product}', [ProductController::class, 'show']);
});

Route::prefix('inspirations')->group(function () {
    Route::get('', [InspirationController::class, 'index']);
});
