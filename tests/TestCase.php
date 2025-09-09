<?php
declare(strict_types=1);

namespace Bazegel\Monobank\tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Bazegel\Monobank\Providers\MonobankServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            MonobankServiceProvider::class,
        ];
    }
}
