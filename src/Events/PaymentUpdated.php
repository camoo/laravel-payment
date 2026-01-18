<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Events;

use Camoo\LaravelPayment\Contracts\PaymentResource;

final class PaymentUpdated
{
    public function __construct(public readonly PaymentResource $payment)
    {
    }
}
