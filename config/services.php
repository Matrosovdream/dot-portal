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

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],
    'authorize_net' => [
        'api_login_id' => env('ANET_API_LOGIN_ID'),
        'transaction_key' => env('ANET_TRANSACTION_KEY'),
        'sandbox' => env('AUTHORIZE_NET_SANDBOX', true) ?? true,
    ],
    'saferweb' => [
        'api_key' => env('SAFERWEB_API_KEY'),
    ],

    // Transportation.gov open data (Socrata). Public; an app token raises rate limits.
    'transpgov' => [
        'api_key' => env('TRANSPGOV_API_KEY'),
    ],

    // ---------------------------------------------------------------------
    // Third-party integrations (see App\Mixins\Integrations + App\Services)
    // ---------------------------------------------------------------------

    // FMCSA QCMobile / SAFER web services — official carrier data by USDOT.
    'fmcsa' => [
        'web_key' => env('FMCSA_WEB_KEY'),
    ],

    // NHTSA vPIC (VIN decode) + Safety (recalls/complaints/ratings) are public,
    // keyless APIs; base URLs live in their clients. No credentials required.

    // Twilio — outbound SMS reminders (expiring CDL / medical card / drug test).
    'twilio' => [
        'sid' => env('TWILIO_ACCOUNT_SID'),
        'token' => env('TWILIO_AUTH_TOKEN'),
        'from' => env('TWILIO_FROM'),
    ],

    // Stripe — alternative payment gateway / ACH.
    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    // Plaid — bank account verification / ACH for subscription billing.
    'plaid' => [
        'client_id' => env('PLAID_CLIENT_ID'),
        'secret' => env('PLAID_SECRET'),
        'env' => env('PLAID_ENV', 'sandbox'),
    ],

    // Smarty (SmartyStreets) — US address validation for company/driver addresses.
    'smarty' => [
        'auth_id' => env('SMARTY_AUTH_ID'),
        'auth_token' => env('SMARTY_AUTH_TOKEN'),
    ],

    // Motor Vehicle Records aggregator (e.g. SambaSafety). Provider-agnostic.
    'mvr' => [
        'provider' => env('MVR_PROVIDER', 'sambasafety'),
        'base_url' => env('MVR_BASE_URL'),
        'api_key' => env('MVR_API_KEY'),
    ],

    // FMCSA Drug & Alcohol Clearinghouse (via C/TPA partner — no open API).
    'clearinghouse' => [
        'base_url' => env('CLEARINGHOUSE_BASE_URL'),
        'api_key' => env('CLEARINGHOUSE_API_KEY'),
    ],

    // FMCSA Pre-Employment Screening Program (PSP) — driver hiring history.
    'psp' => [
        'base_url' => env('PSP_BASE_URL'),
        'account_id' => env('PSP_ACCOUNT_ID'),
        'api_key' => env('PSP_API_KEY'),
    ],

    // DocuSign — e-signature for driver onboarding / lease documents.
    'docusign' => [
        'base_uri' => env('DOCUSIGN_BASE_URI', 'https://demo.docusign.net'),
        'account_id' => env('DOCUSIGN_ACCOUNT_ID'),
        'integration_key' => env('DOCUSIGN_INTEGRATION_KEY'),
        'access_token' => env('DOCUSIGN_ACCESS_TOKEN'),
    ],

    // OCR — extract data from uploaded CDL / medical card images.
    'ocr' => [
        'provider' => env('OCR_PROVIDER', 'textract'),
        'base_url' => env('OCR_BASE_URL'),
        'api_key' => env('OCR_API_KEY'),
    ],

    // Samsara — ELD / telematics (HOS logs, vehicle locations, DVIRs).
    'samsara' => [
        'api_token' => env('SAMSARA_API_TOKEN'),
        'base_url' => env('SAMSARA_BASE_URL', 'https://api.samsara.com'),
    ],
];
