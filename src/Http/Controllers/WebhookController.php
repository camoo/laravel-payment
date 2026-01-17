<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Http\Controllers;

use Camoo\LaravelPayment\Services\CamooPayManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class WebhookController extends Controller
{
    public function __invoke(Request $request, CamooPayManager $camooPay): JsonResponse
    {
        // Payload shape depends on your API webhook format.
        // Assume it contains "payment" or "cashOut" or similar.
        $payload = $request->all();

        // Minimal safe extraction (adjust to your real webhook JSON)
        $payment = (object)($payload['payment'] ?? $payload['cashOut'] ?? $payload);

        $camooPay->emitPaymentEvents($payment);

        return response()->json(['ok' => true]);
    }
}
