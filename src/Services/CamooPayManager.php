<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Services;

use Camoo\LaravelPayment\Events\PaymentFailed;
use Camoo\LaravelPayment\Events\PaymentSucceeded;
use Camoo\LaravelPayment\Events\PaymentUpdated;
use Camoo\Payment\Api\AccountApi;
use Camoo\Payment\Api\PaymentApi;
use Illuminate\Contracts\Events\Dispatcher;

final class CamooPayManager
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
    public function emitPaymentEvents(object $paymentModel): void
    {
        $status = strtoupper((string)($paymentModel->status ?? ''));

        $this->events->dispatch(new PaymentUpdated($paymentModel));

        if (in_array($status, ['SUCCESS', 'CONFIRMED'], true)) {
            $this->events->dispatch(new PaymentSucceeded($paymentModel));

            return;
        }

        if (in_array($status, ['FAILED', 'CANCELED', 'ERRORED'], true)) {
            $this->events->dispatch(new PaymentFailed($paymentModel));
        }
    }
}
