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

    'gemini' => [
        'project' => env('GEMINI_PROJECT'),
        'secret' => env('GEMINI_SECRET'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URL'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URL'),
    ],

    // 'dracoscopia' => [
    //     'api_url' => env('DRACOSCOPIA_API_URL'),
    //     'api_key' => env('DRACOSCOPIA_API_KEY'),
    // ],

    // 's3' => [
    //     'bucket' => env('AWS_BUCKET'),
    //     'url' => env('AWS_URL'),
    // ],

    'dezgo' => [
        'api_key' => env('DEZGO_API_KEY'),
    ],

    'stripe' => [
        'secret' => env('STRIPE_SECRET', 'whsec_pMBLWharUgzUZJ9jv9oZ9n6wL7NRoITE'),
    ],

];
