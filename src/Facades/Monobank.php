<?php
declare(strict_types=1);

namespace Bazegel\Monobank\Facades;

use Illuminate\Support\Facades\Facade;
use Bazegel\Monobank\Services\MonobankService;

class Monobank extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MonobankService::class;
    }
}
