<?php

declare(strict_types=1);

namespace Camoo\LaravelPayment\Facades;

use Camoo\LaravelPayment\Services\CamooPayManager;
use Illuminate\Support\Facades\Facade;

final class CamooPay extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CamooPayManager::class;
    }
}
