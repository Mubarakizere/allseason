<?php

return [
    'provider' => env('PAYMENT_PROVIDER', 'stripe'),

    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'public' => env('STRIPE_PUBLIC'),
    ],

    'paystack' => [
        'secret' => env('PAYSTACK_SECRET'),
        'public' => env('PAYSTACK_PUBLIC'),
    ],

    'weflexfy' => [
        'base_url' => env('WEFLEXFY_BASE_URL', 'https://api.weflexfy.com'),
        'access_key' => env('WEFLEXFY_ACCESS_KEY', ''),
        'secret_key' => env('WEFLEXFY_SECRET_KEY', ''),
        'recipient_number' => env('WEFLEXFY_RECIPIENT_NUMBER', ''),
        'currency' => env('WEFLEXFY_CURRENCY', 'RWF'),
    ],
];
