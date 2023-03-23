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

    'authorization' => [
        'url' => env('AUTHORIZATION_URL', 'https://run.mocky.io/v3/'),
        'token' => env('AUTHORIZATION_TOKEN', '8fafdd68-a090-496f-8c9a-3442cf30dae6'),
        'timeout' => env('AUTHORIZATION_TIMEOUT', 25),
    ],

    'notification' => [
        'url' => env('NOTIFICATION_URL', 'http://o4d9z.mocklab.io/'),
        'timeout' => env('NOTIFICATION_TIMEOUT', 25),
    ],

];
