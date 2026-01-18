<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Tests\Http\Controllers;

use Camoo\LaravelPayment\Contracts\CamooPaymentManagerInterface;
use Camoo\LaravelPayment\Dto\WebhookPaymentResource;
use Camoo\LaravelPayment\Http\Controllers\WebhookController;
use Camoo\LaravelPayment\Tests\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[CoversClass(WebhookController::class)]
#[Group('camoo_pay')]

final class WebhookControllerTest extends TestCase
{
    public function testInvokeEmitsEventAndReturnsOk(): void
    {
        $mockManager = $this->createMock(CamooPaymentManagerInterface::class);

        $mockManager->expects($this->once())
            ->method('emitPaymentEvents')
            ->with($this->callback(function (WebhookPaymentResource $obj) {
                return $obj->getId() === 'p123'
                    && $obj->getStatus() === 'SUCCESS'
                    && $obj->getStatusTime()->format(DATE_ATOM) === '2026-01-02T23:14:03+01:00'
                    && $obj->getExternalReference() === 'ref123';
            }));

        $this->app->instance(CamooPaymentManagerInterface::class, $mockManager);

        $request = Request::create('/webhook', 'GET', [
            'payment_id' => 'p123',
            'status' => 'success',
            'status_time' => '2026-01-02T23:14:03+01:00',
            'trx' => 'ref123',
        ]);

        $controller = new WebhookController();

        $response = $controller($request, $mockManager);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['ok' => true]),
            $response->getContent()
        );
    }
}
