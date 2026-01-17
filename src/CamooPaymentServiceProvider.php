<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment;

use Camoo\LaravelPayment\Contracts\WebhookSignatureVerifier;
use Camoo\LaravelPayment\Services\CamooPayManager;
use Camoo\LaravelPayment\Services\WebhookSignature\HmacSha256Verifier;
use Camoo\Payment\Api\AccountApi;
use Camoo\Payment\Api\PaymentApi;
use Camoo\Payment\Http\Client as PaymentClient;
use Illuminate\Support\ServiceProvider;

final class CamooPaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/camoo-payment.php', 'camoo-payment');

        $this->app->singleton(PaymentClient::class, function () {
            return new PaymentClient(
                apiKey: config('camoo-payment.api_key'),
                apiSecret: config('camoo-payment.api_secret'),
                httpClient: null,
                debug: (bool)config('camoo-payment.debug'),
                apiVersion: (string)config('camoo-payment.api_version')
            );
        });

        $this->app->singleton(PaymentApi::class, fn ($app) => new PaymentApi($app->make(PaymentClient::class)));
        $this->app->singleton(AccountApi::class, fn ($app) => new AccountApi($app->make(PaymentClient::class)));

        // Cashier-like manager
        $this->app->singleton(CamooPayManager::class, fn ($app) => new CamooPayManager(
            paymentApi: $app->make(PaymentApi::class),
            accountApi: $app->make(AccountApi::class),
            events: $app['events'],
        ));

        // Webhook signature verifier
        $this->app->bind(WebhookSignatureVerifier::class, fn () => new HmacSha256Verifier(
            secret: (string)config('camoo-payment.webhooks.secret', ''),
            signatureHeader: (string)config('camoo-payment.webhooks.signature_header', 'X-Camoo-Signature'),
            timestampHeader: (string)config('camoo-payment.webhooks.timestamp_header', 'X-Camoo-Timestamp'),
            toleranceSeconds: (int)config('camoo-payment.webhooks.tolerance_seconds', 300),
        ));
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/camoo-payment.php' => config_path('camoo-payment.php'),
        ], 'camoo-payment-config');

        $this->loadRoutesFrom(__DIR__ . '/../routes/webhooks.php');
    }
}
