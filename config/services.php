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

    /*
    |--------------------------------------------------------------------------
    | Twilio WhatsApp
    |--------------------------------------------------------------------------
    |
    | Credentials for the Twilio API used to send WhatsApp messages.
    | TWILIO_SID          — Account SID from https://console.twilio.com
    | TWILIO_TOKEN        — Auth Token from https://console.twilio.com
    | TWILIO_WHATSAPP_FROM — WhatsApp-enabled number (e.g. +14155238886 for sandbox)
    |
    */
    'twilio' => [
        'sid'            => env('TWILIO_SID'),
        'token'          => env('TWILIO_TOKEN'),
        'whatsapp_from'  => env('TWILIO_WHATSAPP_FROM'),
    ],

];
