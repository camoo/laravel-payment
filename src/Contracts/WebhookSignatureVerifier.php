<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Contracts;

use Illuminate\Http\Request;

interface WebhookSignatureVerifier
{
    public function verify(Request $request): bool;
}
