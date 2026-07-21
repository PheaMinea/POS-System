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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Bakong (NBC) Payment Gateway
    |--------------------------------------------------------------------------
    */

    'bakong' => [
        'base_url' => env('BAKONG_BASE_URL', 'https://api-bakong.nbc.gov.kh/v1'),
        'token' => env('BAKONG_TOKEN'),
        'merchant_id' => env('BAKONG_MERCHANT_ID'),
        'merchant_account_id' => env('BAKONG_MERCHANT_ACCOUNT_ID'),
        'customer_auto_confirm' => env('BAKONG_CUSTOMER_AUTO_CONFIRM', false),
        'http_verify' => env('BAKONG_HTTP_VERIFY', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Telegram Bot
    |--------------------------------------------------------------------------
    */

    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'chat_id' => env('TELEGRAM_CHAT_ID'),
        'enabled' => env('TELEGRAM_RECEIPT_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Google OAuth
    |--------------------------------------------------------------------------
    */

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
        'guzzle' => [
            'verify' => env('GOOGLE_HTTP_VERIFY', true),
        ],
    ],

];
