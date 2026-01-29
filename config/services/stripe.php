<?php

return [
    'api_secret'     => env('STRIPE_API_SECRET', ''),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET', ''),
];
