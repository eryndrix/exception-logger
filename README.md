# Exception Logger

A Laravel package for structured exception logging with PSR-3 levels and attribute-based configuration. It allows you to define log levels per exception class using PHP attributes or configuration, and customize which exception details (_stack trace, file, line, etc._) are included in logs based on severity.

[![PHP Version](https://img.shields.io/badge/PHP-^8.5-blue.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-^13.0-red.svg)](https://laravel.com)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

## Features

- **PSR-3 compliant**: Supports all 8 standard log levels (`debug`, `info`, `notice`, `warning`, `error`, `critical`, `alert`, `emergency`)
- **Attribute-Based Configuration**: Use `#[Log]` attribute to define log level on exception classes
- **Configurable Context**: Control which exception details to include in logs (_exception class, code, file, line, trace_)
- **Level-Specific Context**: Different context configuration for each log level
- **Exception Level Mapping**: Map exception classes to log levels via config

## Installation

```bash
composer require eryndrix/exception-logger
```

The service provider auto-registers. To publish the configuration file:

```bash
php artisan vendor:publish --tag="exception-logger-config"
```

## Requirements

- PHP ^8.5
- Laravel ^13.0

## Quick Start

1. Use built-in exceptions

```php
use Ekara\Logging\Exceptions\PaginationException;
use Ekara\Logging\Exceptions\UserNotFoundException;
use Ekara\Logging\Exceptions\UnexpectedException;

throw new PaginationException(perPage: 500);    // WARNING
throw new UserNotFoundException();              // WARNING
throw new UnexpectedException(message: 'Oops'); // CRITICAL
```

2. Or create your own

```php
use Ekara\Logging\Attributes\{Level, Log};

#[Log(level: Level::WARNING)]
final class PaginationException extends \DomainException {}

#[Log(level: Level::WARNING)]
final class UserNotFoundException extends \LogicException {}

#[Log(level: Level::CRITICAL)]
final class UnexpectedException extends \RuntimeException {}
```

3. Log exceptions

Inject `ExceptionLoggerInterface` and call `log()`:

```php
use Ekara\Logging\Contracts\ExceptionLoggerInterface;

class MyService
{
    public function __construct(
        private ExceptionLoggerInterface $logger
    ) {}
    
    public function handle(): void
    {
        try {
            // ...
        } catch (\Throwable $e) {
            $this->logger->log(exception: $e);
        }
    }
}
```

The logger resolves the level in this order:

- `#[Log]` attribute on the exception class
- `exception_levels` config mapping
- `default_level` fallback

### Logger Interface

For direct logging without level resolution, use `LoggerInterface`:

```php
use Eryndrix\Logging\Contracts\LoggerInterface;

class MyService
{
    public function __construct(
        private LoggerInterface $logger
    ) {}
    
    public function handle(): void
    {
        $exception = new \RuntimeException(
            message: 'error'
        );
        
        $this->logger->error(
            message: 'Something went wrong',
            e: $exception
        );

        $this->logger->warning(
            message: 'Potential issue',
            e: $exception
        );

        $this->logger->debug(
            message: 'Debug info',
            e: $exception
        );
    }
}
```

## Configuration

The published config (`config/exception-logger.php`) has three sections:

`default_level`

Fallback level for exceptions without explicit configuration:

```php
'default_level' => env('DEFAULT_LOG_LEVEL', 'error')
```

**Tip**: Use `error` in production, `debug` in development.

`exception_levels`

Map exception classes to levels without modifying them:

```php
'exception_levels' => [
    \DomainException::class => 'warning',
    \InvalidArgumentException::class => 'warning',
    \App\Exceptions\PaginationException::class => 'warning',
]
```

`context`

Control which details to include per log level:

```php
'context' => [
    'emergency' => [
        'exception' => true,
        'code' => true,
        'file' => true,
        'line' => true,
        'trace' => true,
    ],
    'error' => [
        'exception' => true,
        'code' => true,
        'file' => true,
        'line' => true,
        'trace' => false,
    ],
    'warning' => [
        'exception' => true,
        'code' => true,
        'file' => false,
        'line' => false,
        'trace' => false,
    ],
    // ... other levels
]
```

Available fields:

| Field       | Description                                   |
|-------------|-----------------------------------------------|
| `exception` | Exception class name                          |
| `code`      | Exception code (_integer_)                    |
| `file`      | File where exception was thrown (_full path_) |
| `line`      | Line number                                   |
| `trace`     | Full stack trace (_can be very verbose_)      |

**Security note**: In production, disable `file`, `line`, and `trace` to avoid exposing internal paths.

## Example Log Output

With the default config, a `PaginationException` produces:

```json
{
  "message": "Per page must be between 1 and 100. Given: 500.",
  "context": {
    "exception": "Ekara\\Logging\\Exceptions\\PaginationException",
    "code": 0,
    "file": "/vendor/ekara/exception-logger/src/Exceptions/PaginationException.php",
    "line": 24
  },
  "level": "warning"
}
```

## Testing

Run package tests:

```bash
./vendor/bin/pest
```
## License

This project is licensed under the [MIT License](https://opensource.org/licenses/MIT).
