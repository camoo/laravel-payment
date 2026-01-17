<?php

declare(strict_types=1);

use Camoo\LaravelPayment\Http\Controllers\WebhookController;
use Camoo\LaravelPayment\Http\Middleware\VerifyCamooWebhookSignature;
use Illuminate\Support\Facades\Route;

Route::post(
    '/' . trim((string)config('camoo-payment.webhooks.route_prefix', 'webhooks/camoo'), '/'),
    WebhookController::class
)->middleware([
    'api',
    VerifyCamooWebhookSignature::class,
])->name('camoo-payment.webhook');
