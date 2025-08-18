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

    'zoom' => [
        'client_id' => env('ZOOM_CLIENT_ID'),
        'client_secret' => env('ZOOM_CLIENT_SECRET'),
        'account_id' => env('ZOOM_ACCOUNT_ID'),
        'host_email' => env('ZOOM_HOST_EMAIL', 'itsabdullah824@gmail.com'),
    ],

    'chatwoot' => [
        'account_id' => 131300,
        'base_url' => 'https://app.chatwoot.com',
        'dashboard_url' => 'https://app.chatwoot.com/app/accounts/131300/dashboard',
        'api_url' => 'https://app.chatwoot.com/api/v1',
        'api_token' => env('CHATWOOT_API_TOKEN', null),
        'website_token' => env('CHATWOOT_WEBSITE_TOKEN', null),
        'webhook_secret' => env('CHATWOOT_WEBHOOK_SECRET', null),
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

];
