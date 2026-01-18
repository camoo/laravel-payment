<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Contracts;

interface PaymentResource
{
    public function getId(): string;

    public function getStatus(): string;

    public function getStatusTime(): \DateTimeInterface;

    public function getExternalReference(): ?string;
}
