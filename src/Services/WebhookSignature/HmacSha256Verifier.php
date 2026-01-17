<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Services\WebhookSignature;

use Camoo\LaravelPayment\Contracts\WebhookSignatureVerifier;
use Illuminate\Http\Request;

final class HmacSha256Verifier implements WebhookSignatureVerifier
{
    public function __construct(
        private readonly string $secret,
        private readonly string $signatureHeader,
        private readonly string $timestampHeader,
        private readonly int $toleranceSeconds
    ) {
    }

    public function verify(Request $request): bool
    {
        // If not configured, do not accidentally "verify" as true.
        if ($this->secret === '') {
            return false;
        }

        $signature = (string)$request->headers->get($this->signatureHeader, '');
        $timestamp = (string)$request->headers->get($this->timestampHeader, '');

        if ($signature === '' || $timestamp === '') {
            return false;
        }

        if (!ctype_digit($timestamp)) {
            return false;
        }

        $ts = (int)$timestamp;
        if (abs(time() - $ts) > $this->toleranceSeconds) {
            return false;
        }

        $payload = (string)$request->getContent();

        // Proposed canonical string:
        // "{timestamp}.{raw_body}"
        $signed = $timestamp . '.' . $payload;

        $expected = hash_hmac('sha256', $signed, $this->secret);

        return hash_equals($expected, $signature);
    }
}
