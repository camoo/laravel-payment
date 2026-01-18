<?php

namespace Camoo\LaravelPayment\Tests;

use Camoo\Payment\Api\PaymentApi;

final class SmokeTest extends TestCase
{
    public function testPackageBootsAndServicesAreResolvable(): void
    {
        $this->assertTrue(
            $this->app->bound(PaymentApi::class)
        );
    }
}
