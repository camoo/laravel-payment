<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Http\Controllers;

use Camoo\LaravelPayment\Contracts\CamooPaymentManagerInterface;
use Camoo\LaravelPayment\Dto\WebhookPaymentResource;
use DateTimeImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class WebhookController extends Controller
{
    public function __invoke(Request $request, CamooPaymentManagerInterface $camooPay): JsonResponse
    {

        $payment = new WebhookPaymentResource(
            paymentId: (string)$request->query('payment_id'),
            status: strtoupper((string)$request->query('status')), // case-insensitive normalization
            statusTime: new DateTimeImmutable((string)$request->query('status_time')),
            trx: $request->query('trx')
        );

        // Emit internal event / handler
        $camooPay->emitPaymentEvents($payment);

        return new JsonResponse(['ok' => true]);
    }
}
