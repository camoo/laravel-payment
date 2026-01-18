<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Services;

use Camoo\LaravelPayment\Contracts\CamooPaymentManagerInterface;
use Camoo\LaravelPayment\Contracts\PaymentResource;
use Camoo\LaravelPayment\Events\PaymentFailed;
use Camoo\LaravelPayment\Events\PaymentSucceeded;
use Camoo\LaravelPayment\Events\PaymentUpdated;
use Camoo\Payment\Api\AccountApi;
use Camoo\Payment\Api\PaymentApi;
use Illuminate\Contracts\Events\Dispatcher;

final class CamooPayManager implements CamooPaymentManagerInterface
{
    public function __construct(
        private readonly PaymentApi $paymentApi,
        private readonly AccountApi $accountApi,
        private readonly Dispatcher $events,
    ) {
    }

    public function cashout(array $payload): object
    {
        return $this->paymentApi->cashout($payload);
    }

    public function verify(string $id): object
    {
        return $this->paymentApi->verify($id);
    }

    public function account(): object
    {
        return $this->accountApi->get();
    }

    /**
     * Call this from webhook handling after you parse the payload.
     * Emits events based on status.
     */
    public function emitPaymentEvents(PaymentResource $payment): void
    {
        $status = strtoupper($payment->getStatus());

        match ($status) {
            'SUCCESS', 'CONFIRMED' => $this->events->dispatch(new PaymentSucceeded($payment)),
            'FAILED', 'CANCELED', 'ERRORED' => $this->events->dispatch(new PaymentFailed($payment)),
            default => $this->events->dispatch(new PaymentUpdated($payment)), // pending, processing, etc.
        };
    }
}
