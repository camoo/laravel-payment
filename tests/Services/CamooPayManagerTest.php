<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Tests\Services;

use Camoo\LaravelPayment\Contracts\PaymentResource;
use Camoo\LaravelPayment\Events\PaymentFailed;
use Camoo\LaravelPayment\Events\PaymentSucceeded;
use Camoo\LaravelPayment\Events\PaymentUpdated;
use Camoo\LaravelPayment\Services\CamooPayManager;
use Camoo\LaravelPayment\Tests\TestCase;
use Camoo\Payment\Api\AccountApi;
use Camoo\Payment\Api\PaymentApi;
use Illuminate\Contracts\Events\Dispatcher;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;

#[CoversClass(CamooPayManager::class)]
#[Group('camoo_pay')]
final class CamooPayManagerTest extends TestCase
{
    private Dispatcher $events;

    private CamooPayManager $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $paymentApi = $this->createMock(PaymentApi::class);
        $accountApi = $this->createMock(AccountApi::class);
        $this->events = $this->createMock(Dispatcher::class);

        $this->manager = new CamooPayManager(
            paymentApi: $paymentApi,
            accountApi: $accountApi,
            events: $this->events,
        );
    }

    public function testEmitsPaymentSucceededForSuccess(): void
    {
        $payment = $this->paymentWithStatus('SUCCESS');

        $this->events->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(PaymentSucceeded::class));

        $this->manager->emitPaymentEvents($payment);
    }

    public function testEmitsPaymentSucceededForConfirmed(): void
    {
        $payment = $this->paymentWithStatus('confirmed'); // case-insensitive

        $this->events->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(PaymentSucceeded::class));

        $this->manager->emitPaymentEvents($payment);
    }

    public function testEmitsPaymentFailedForFailed(): void
    {
        $payment = $this->paymentWithStatus('FAILED');

        $this->events->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(PaymentFailed::class));

        $this->manager->emitPaymentEvents($payment);
    }

    public function testEmitsPaymentUpdatedForOtherStatuses(): void
    {
        $payment = $this->paymentWithStatus('PENDING');

        $this->events->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(PaymentUpdated::class));

        $this->manager->emitPaymentEvents($payment);
    }

    /** Helper to create a PaymentResource mock with a given status. */
    private function paymentWithStatus(string $status): PaymentResource
    {
        $payment = $this->createMock(PaymentResource::class);
        $payment->method('getStatus')->willReturn($status);

        return $payment;
    }
}
