<?php

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/health.php';

Route::prefix('docs')->group(function () {
    Scramble::registerUiRoute('api');
    Scramble::registerJsonSpecificationRoute('api.json');
});

if (app()->environment('local')) {
    Route::get('/preview/mail/{view}', function ($view) {
        return view("mail.$view", [
            'name' => 'Test User',
            'code' => '123456',
        ]);
    });
}

Route::get('/debug', function () {
    return [
        'scheme'           => request()->getScheme(),
        'secure'           => request()->isSecure(),
        'https_server_var' => request()->server('HTTPS'),
        'forwarded_proto'  => request()->header('X-Forwarded-Proto'),
    ];
});
