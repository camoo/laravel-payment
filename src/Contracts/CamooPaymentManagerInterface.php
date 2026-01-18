<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Contracts;

interface CamooPaymentManagerInterface
{
    /**
     * Initiate a cashout transaction.
     *
     * @param array<string, mixed> $payload The payload containing cashout details.
     *
     * @return object The response object from the cashout operation.
     */
    public function cashout(array $payload): object;

    /**
     * Verify the status of a payment transaction by its ID.
     *
     * @param string $id The unique identifier of the payment transaction.
     *
     * @return object The response object containing the payment status.
     */
    public function verify(string $id): object;

    /**
     * Retrieve account information.
     *
     * @return object The response object containing account details.
     */
    public function account(): object;

    /**
     * Call this from webhook handling after you parse the payload.
     * Emits events based on status.
     */
    public function emitPaymentEvents(PaymentResource $payment): void;
}
