<?php

namespace Camoo\LaravelPayment\Tests;

use Camoo\LaravelPayment\CamooPaymentServiceProvider;
use Orchestra\Testbench\TestCase;

final class ServiceProviderTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [CamooPaymentServiceProvider::class];
    }

    public function test_service_provider_boots(): void
    {
        $this->assertTrue(true);
    }
}
