<?php

namespace Camoo\LaravelPayment\Tests;

use Camoo\Payment\Api\PaymentApi;

final class SmokeTest extends TestCase
{
    public function test_package_boots_and_services_are_resolvable(): void
    {
        $this->assertTrue(
            $this->app->bound(PaymentApi::class)
        );
    }
}
