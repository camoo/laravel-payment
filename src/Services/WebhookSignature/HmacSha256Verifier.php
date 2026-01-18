<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Services\WebhookSignature;

use Camoo\LaravelPayment\Contracts\WebhookSignatureVerifier;
use Illuminate\Http\Request;

final class HmacSha256Verifier implements WebhookSignatureVerifier
{
    public function __construct(
        private readonly string $secret,
    ) {
    }

    public function verify(Request $request): bool
    {
        $signature = $request->query('sig');

        if (!$signature || !$this->secret) {
            return false;
        }

        // Collect query params except sig
        $params = $request->query();
        unset($params['sig']);

        // Normalize status (case-insensitive)
        if (isset($params['status'])) {
            $params['status'] = strtoupper((string)$params['status']);
        }

        ksort($params);

        $queryString = http_build_query(
            $params,
            '',
            '&',
            PHP_QUERY_RFC3986
        );

        $expected = hash_hmac('sha256', $queryString, $this->secret);

        return hash_equals($expected, (string)$signature);
    }
}
