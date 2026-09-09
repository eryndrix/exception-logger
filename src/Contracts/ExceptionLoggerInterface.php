<?php declare(strict_types=1);

namespace Eryndrix\Logging\Contracts;

interface ExceptionLoggerInterface
{
    /**
     * @phpstan-param \Throwable $exception
     * @phpstan-return void
     */
    public function log(\Throwable $exception): void;
}
