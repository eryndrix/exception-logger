<?php declare(strict_types=1);

namespace Eryndrix\Logging;

use Eryndrix\Logging\Contracts\LoggerInterface;
use Eryndrix\Logging\Contracts\ExceptionLoggerInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

final class ExceptionLoggerServiceProvider extends ServiceProvider
    implements DeferrableProvider
{
    /**
     * @phpstan-return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            path: __DIR__ . '/../config/exception-logger.php',
            key: 'exception-logger'
        );

        $this->app->singleton(
            abstract: LoggerInterface::class,
            concrete: Logger::class
        );

        $this->app->singleton(
            abstract: ExceptionLoggerInterface::class,
            concrete: ExceptionLogger::class
        );
    }

    /**
     * @phpstan-return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                paths: [
                    __DIR__ . '/../config/exception-logger.php' => config_path(
                        path: 'exception-logger.php'
                    )
                ],
                groups: 'exception-logger-config'
            );
        }
    }

    /**
     * @phpstan-return array<int, class-string>
     */
    public function provides(): array
    {
        return [
            LoggerInterface::class,
            ExceptionLoggerInterface::class,
        ];
    }
}
