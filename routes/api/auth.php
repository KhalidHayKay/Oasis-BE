<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Auth\EmailVerificationController;

Route::prefix('auth')->group(function () {
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

    Route::get('/provider/{provider}', [SocialAuthController::class, 'redirect']);
    Route::get('/provider/{provider}/callback', [SocialAuthController::class, 'callback']);

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/social-login', [AuthController::class, 'socialLogin']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::prefix('/email')->group(function () {
        Route::post('/verify', [EmailVerificationController::class, 'verify']);
        Route::post('/send-code', [EmailVerificationController::class, 'code']);
    });

    Route::prefix('/password')->group(function () {
        Route::post('/forgot', [PasswordController::class, 'token']);
        Route::post('/reset', [PasswordController::class, 'reset']);
    });
});

Route::middleware(['auth:sanctum', 'verified'])->get('/check-email', function () {
    return response()->json(['message' => 'Email is verified']);
});
