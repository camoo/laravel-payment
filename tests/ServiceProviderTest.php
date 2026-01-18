<?php

namespace Camoo\LaravelPayment\Tests;

use Camoo\LaravelPayment\CamooPaymentServiceProvider;
use Orchestra\Testbench\TestCase;

final class ServiceProviderTest extends TestCase
{
    public function testServiceProviderBoots(): void
    {
        $this->assertTrue(true);
    }

    protected function getPackageProviders($app): array
    {
        return [CamooPaymentServiceProvider::class];
    }
}
