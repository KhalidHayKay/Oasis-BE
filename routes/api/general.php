<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InspirationController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('categories')->group(function () {
    Route::get('', [CategoryController::class, 'index']);
    Route::get('/{slug}', [CategoryController::class, 'show']);
});

Route::prefix('products')->group(function () {
    Route::get('', [ProductController::class, 'index']);
    Route::get('top', [ProductController::class, 'top']);
    Route::get('/{product}', [ProductController::class, 'show']);
});

Route::prefix('inspirations')->group(function () {
    Route::get('', [InspirationController::class, 'index']);
});

Route::get('tags', [App\Http\Controllers\TagController::class, 'index']);

Route::prefix('blogs')->group(function () {
    Route::get('/', [BlogController::class, 'index']);
    Route::get('/{slug}', [BlogController::class, 'show']);
});
