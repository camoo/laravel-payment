<?php

declare(strict_types=1);

return [
    'api_key' => env('CAMOO_PAYMENT_API_KEY'),
    'api_secret' => env('CAMOO_PAYMENT_API_SECRET'),

    // If SDK supports base url internally, keep; otherwise just for your own links and webhook signatures.
    'base_url' => env('CAMOO_PAYMENT_BASE_URL', 'https://api.camoo.cm'),
    'api_version' => env('CAMOO_PAYMENT_API_VERSION', 'v1'),

    'debug' => env('CAMOO_PAYMENT_DEBUG', false),

    /**
     * Webhook signature verification.
     *
     * IMPORTANT:
     * The signature scheme MUST match what your Camoo API actually sends.
     * Below is a solid default scheme (HMAC SHA256) you can adopt server-side.
     */
    'webhooks' => [
        'enabled' => env('CAMOO_PAYMENT_WEBHOOK_VERIFY', true),
        'secret' => env('CAMOO_PAYMENT_WEBHOOK_SECRET'), // recommended to be different from api_secret
        'signature_header' => env('CAMOO_PAYMENT_WEBHOOK_SIGNATURE_HEADER', 'X-Camoo-Signature'),
        'timestamp_header' => env('CAMOO_PAYMENT_WEBHOOK_TIMESTAMP_HEADER', 'X-Camoo-Timestamp'),
        'tolerance_seconds' => env('CAMOO_PAYMENT_WEBHOOK_TOLERANCE', 300),
        'route_prefix' => env('CAMOO_PAYMENT_WEBHOOK_PREFIX', 'webhooks/camoo'),
    ],
];
