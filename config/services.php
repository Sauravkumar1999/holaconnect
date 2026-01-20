<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'viva' => [
        'merchant_id' => env('VIVA_MERCHANT_ID'),
        'api_key' => env('VIVA_API_KEY'),
        'environment' => env('VIVA_ENVIRONMENT', 'demo'), // 'demo' or 'production'
        'registration_amount' => env('VIVA_REGISTRATION_AMOUNT', 100.00),

        // API URLs Configuration
        'urls' => [
            'demo' => [
                'orders' => 'https://demo-api.vivapayments.com/checkout/v2/orders',
                'transactions' => 'https://demo-api.vivapayments.com/api/transactions',
                'orders_detail' => 'https://demo-api.vivapayments.com/api/orders',
                'checkout' => 'https://demo.vivapayments.com/web/checkout',
            ],
            'production' => [
                'orders' => 'https://api.vivapayments.com/checkout/v2/orders',
                'transactions' => 'https://api.vivapayments.com/api/transactions',
                'orders_detail' => 'https://api.vivapayments.com/api/orders',
                'checkout' => 'https://www.vivapayments.com/web/checkout',
            ],
        ],
    ],

];
