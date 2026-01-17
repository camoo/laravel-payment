<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Http\Middleware;

use Camoo\LaravelPayment\Contracts\WebhookSignatureVerifier;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class VerifyCamooWebhookSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!config('camoo-payment.webhooks.enabled', true)) {
            return $next($request);
        }

        /** @var WebhookSignatureVerifier $verifier */
        $verifier = app(WebhookSignatureVerifier::class);

        if (!$verifier->verify($request)) {
            return response()->json(['message' => 'Invalid webhook signature'], 401);
        }

        return $next($request);
    }
}
