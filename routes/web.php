<?php

use App\Http\Controllers\Webhooks\StripeWebhookController;
use Dedoc\Scramble\Scramble;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['status' => 'ok', 'database' => 'connected'], 200);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'database' => 'not connected'], 500);
    }
});

Route::withoutMiddleware([VerifyCsrfToken::class])->prefix('webhooks')->group(function () {
    Route::post('/stripe', [StripeWebhookController::class, 'handle']);
});

Route::prefix('docs')->group(function () {
    Scramble::registerUiRoute('api');
    Scramble::registerJsonSpecificationRoute('api.json');
});

if (app()->environment('local')) {
    Route::get('/mail/view/{view}', function ($view) {
        return view("mail.$view", [
            'name' => 'Test User',
            'code' => '123456',
        ]);
    });
}
