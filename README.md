# Camoo Payment for Laravel

A **first-class Laravel integration** for the **Camoo Payment API**, built on top of the official PHP SDK.
This package provides a clean, expressive, and testable API for handling **cashouts**, **payment verification**, **account balance**, and **webhook validation** in Laravel applications.

---

## Features

* ✅ Laravel-native Service Provider
* ✅ Clean dependency injection (no static SDK usage)
* ✅ Cashier-like manager (`CamooPayManager`)
* ✅ Secure webhook signature verification (HMAC SHA256)
* ✅ PSR-4 compliant & framework-agnostic SDK underneath
* ✅ Fully documented via OpenAPI + Postman

---

## Documentation & Resources

* 📘 **OpenAPI Documentation**
  [read](https://redocly.github.io/redoc/?url=https://raw.githubusercontent.com/camoo/payment-api/main/openapi.yaml)

* 📦 **Postman Collection**
  [download](https://raw.githubusercontent.com/camoo/payment-api/main/docs/postman/Camoo-Payment-API.postman_collection.json)

* 🧩 **Core PHP SDK**
  [repo](https://github.com/camoo/payment-api)

---

## Requirements

* **PHP 8.1+**
* **Laravel 10+**
* Composer

---

## Installation

Install the package via Composer:

```bash
composer require camoo/laravel-mobile-money-payment
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag=camoo-payment-config
```

---

## Configuration

Update your `.env` file:

```env
CAMOO_PAYMENT_API_KEY=your_api_key
CAMOO_PAYMENT_API_SECRET=your_api_secret
CAMOO_PAYMENT_API_VERSION=v1
CAMOO_PAYMENT_DEBUG=false

CAMOO_WEBHOOK_SECRET=your_webhook_secret
```

Config file: `config/camoo-payment.php`

```php
return [
    'api_key'     => env('CAMOO_PAYMENT_API_KEY'),
    'api_secret'  => env('CAMOO_PAYMENT_API_SECRET'),
    'api_version' => env('CAMOO_PAYMENT_API_VERSION', 'v1'),
    'debug'       => env('CAMOO_PAYMENT_DEBUG', false),

    'webhooks' => [
        'secret'            => env('CAMOO_WEBHOOK_SECRET'),
        'signature_header'  => 'X-Camoo-Signature',
        'timestamp_header'  => 'X-Camoo-Timestamp',
        'tolerance_seconds' => 300,
    ],
];
```

---

## Getting Started

This package exposes a **Cashier-like manager** you can inject anywhere in your application.

### Dependency Injection (Recommended)

```php
use Camoo\LaravelPayment\Services\CamooPayManager;

class PaymentController
{
    public function __construct(
        private CamooPayManager $camooPay
    ) {}

    public function cashout()
    {
        return $this->camooPay->cashout([
            'phone_number' => '+237612345678',
            'amount'       => 5000,
            'notification_url' => route('webhooks.camoo'),
        ]);
    }
}
```

---

## Cashout Example

```php
$response = $camooPay->cashout([
    'phone_number'      => '+237612345678',
    'amount'            => 5000,
    'currency'          => 'XAF',
    'external_reference'=> 'ORDER-12345',
]);

echo $response->id;
echo $response->status;
```

---

## Verify a Payment

```php
$payment = $camooPay->verify('934ca3f6-dad6-4503-ae36-c01ca3354183');

echo $payment->status;
```

---

## Get Account Balance

```php
$account = $camooPay->account();

echo $account->balance->amount;
echo $account->balance->currency->value;
```

---

## Webhooks

Webhook routes are automatically loaded.

### Example Endpoint

```http
POST /webhooks/camoo
```

### Signature Verification

Webhook payloads are **automatically verified** using:

* HMAC SHA256
* Timestamp tolerance
* Configurable headers

You can inject the verifier manually if needed:

```php
use Camoo\LaravelPayment\Contracts\WebhookSignatureVerifier;

public function handle(Request $request, WebhookSignatureVerifier $verifier)
{
    $verifier->verify($request);
}
```

---

## Error Handling

All API errors throw a unified exception:

```php
use Camoo\Payment\Exception\ApiException;

try {
    $camooPay->cashout([...]);
} catch (ApiException $e) {
    logger()->error($e->getMessage(), [
        'code' => $e->getCode(),
    ]);
}
```

---

## Testing

You can easily mock the manager in tests:

```php
$this->mock(CamooPayManager::class)
    ->shouldReceive('cashout')
    ->once()
    ->andReturn($fakePayment);
```

---

## Architecture Overview

```
Laravel App
 └── CamooPayManager
      ├── PaymentApi
      ├── AccountApi
      └── PaymentClient (SDK)
           └── HTTP Transport
```

This ensures:

* Clean separation of concerns
* Easy framework portability
* Long-term maintainability

---

## Contributing

1. Fork the repository
2. Create a feature branch
3. Follow **PSR-12**
4. Add tests where applicable
5. Submit a pull request

---

## License

This package is open-source software licensed under the **MIT License**.

---

## Final Notes

This package is designed to feel **native to Laravel**, while remaining **strictly aligned with the OpenAPI contract**.

If you need:

* Facade support
* Idempotency helpers
* Retry middleware
* Queue-based cashouts
* Multi-account support
Feel free to open an issue or submit a PR!