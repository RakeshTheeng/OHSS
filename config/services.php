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

    'esewa' => [
        'product_code' => env('ESEWA_PRODUCT_CODE', 'EPAYTEST'),
        'secret_key' => env('ESEWA_SECRET_KEY', '8gBm/:&EnhH.1/q'),
        'payment_url' => env('ESEWA_PAYMENT_URL', 'https://rc-epay.esewa.com.np/api/epay/main/v2/form'),
        'status_check_url' => env('ESEWA_STATUS_CHECK_URL', 'https://rc.esewa.com.np/api/epay/transaction/status/'),
    ],

    'khalti' => [
        'public_key' => env('KHALTI_PUBLIC_KEY', 'test_public_key_dc74e0fd57cb46cd93832aee0a390234'),
        'secret_key' => env('KHALTI_SECRET_KEY', 'test_secret_key_f59e8b7c18b4499bb7ba1c96fcb75e96'),
        'verify_url' => env('KHALTI_VERIFY_URL', 'https://khalti.com/api/v2/payment/verify/'),
    ],

];
