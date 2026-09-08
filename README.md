# Exception Logger

Laravel package for exception logging with PSR-3 levels and attribute-based configuration.

## Features

- **PSR-3 Log Levels**: Support for all 8 PSR-3 log levels (debug, info, notice, warning, error, critical, alert, emergency)
- **Attribute-Based Configuration**: Use `#[Log]` attribute to define log level on exception classes
- **Configurable Context**: Control which exception details to include in logs (exception class, code, file, line, trace)
- **Level-Specific Context**: Different context configuration for each log level
- **Exception Level Mapping**: Map exception classes to log levels via config

## Installation

```bash
composer require eryndrix/exception-logger
```

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag="exception-logger-config"
```

Config file (`config/exception-logger.php`):

```php
return [
    'default_level' => env('DEFAULT_LOG_LEVEL', 'error'),
    
    'exception_levels' => [
        \DomainException::class => 'warning',
        \InvalidArgumentException::class => 'warning',
    ],
    
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
        'info' => [
            'exception' => true,
            'code' => false,
            'file' => false,
            'line' => false,
            'trace' => false,
        ],
        // ... other levels
    ],
];
```

## Usage

### Basic Usage

The package automatically registers service provider. Use dependency injection:

```php
use Eryndrix\Logging\Contracts\ExceptionLoggerInterface;

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
            $this->logger->log($e);
        }
    }
}
```

### Log Attribute

Define log level on exception class:

```php
use Eryndrix\Logging\Attributes\{Level, Log};

#[Log(level: Level::WARNING)]
class BusinessException extends \DomainException {}

#[Log(level: Level::CRITICAL)]
class CriticalException extends \RuntimeException {}
```

### Logger Interface

Use `LoggerInterface` for direct logging:

```php
use Eryndrix\Logging\Contracts\LoggerInterface;

class MyService
{
    public function __construct(
        private LoggerInterface $logger
    ) {}
    
    public function handle(): void
    {
        $exception = new \RuntimeException('error');
        
        $this->logger->error('Something went wrong', $exception);
        $this->logger->warning('Potential issue', $exception);
        $this->logger->debug('Debug info', $exception);
    }
}
```

Available methods: `emergency()`, `alert()`, `critical()`, `error()`, `warning()`, `notice()`, `info()`, `debug()`.

## Context Configuration

Control which exception details to include in logs per level:

```php
'context' => [
    'emergency' => [
        'exception' => true,  // Exception class name
        'code' => true,       // Exception code
        'file' => true,       // File where thrown
        'line' => true,       // Line number
        'trace' => true,      // Full stack trace
    ],
    'warning' => [
        'exception' => true,
        'code' => true,
        'file' => false,      // Disable for warnings
        'line' => false,
        'trace' => false,
    ],
    'info' => [
        'exception' => true,
        'code' => false,
        'file' => false,
        'line' => false,
        'trace' => false,
    ],
],
```

## Testing

Run package tests:

```bash
./vendor/bin/pest
```
