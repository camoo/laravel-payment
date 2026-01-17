<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Events;

final class PaymentFailed
{
    public function __construct(public readonly object $payment)
    {
    }
}
