<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Tests;

use Camoo\LaravelPayment\CamooPaymentServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            CamooPaymentServiceProvider::class,
        ];
    }
}
