<?php declare(strict_types=1);

namespace Eryndrix\Logging\Exceptions;

use Eryndrix\Logging\Attributes\{Level, Log};

#[Log(level: Level::CRITICAL)]
final class UnexpectedException extends \RuntimeException
{
    /**
     * @phpstan-param string $message
     * @phpstan-param int $code
     * @phpstan-param \Throwable|null $previous
     */
    public function __construct(
        string $message = 'Unexpected exception occurred.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            message: $message,
            code: $code,
            previous: $previous
        );
    }
}
