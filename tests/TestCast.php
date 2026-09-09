<?php declare(strict_types=1);

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Eryndrix\Logging\Providers\ExceptionLoggerServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ExceptionLoggerServiceProvider::class,
        ];
    }
}
