<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Dto;

use Camoo\LaravelPayment\Contracts\PaymentResource;
use DateTimeInterface;

final class WebhookPaymentResource implements PaymentResource
{
    public function __construct(
        private readonly string $paymentId,
        private readonly string $status,
        private readonly DateTimeInterface $statusTime,
        private readonly ?string $trx = null,
    ) {
    }

    public function getId(): string
    {
        return $this->paymentId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getStatusTime(): DateTimeInterface
    {
        return $this->statusTime;
    }

    public function getExternalReference(): ?string
    {
        return $this->trx;
    }
}
