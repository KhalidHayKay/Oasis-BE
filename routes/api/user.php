<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WaitlistController;

Route::prefix('/waitlist')->group(function () {
    Route::get('', [WaitlistController::class, 'index']);
    Route::post('/new', [WaitlistController::class, 'store']);
    Route::get('/{waitlist}', [WaitlistController::class, 'show']);
});
