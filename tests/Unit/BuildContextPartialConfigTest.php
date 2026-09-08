<?php declare(strict_types=1);

use Eryndrix\Logging\ContextBuilder;

it('builds context with partial config', function (): void {
    $config = [
        'exception' => true,
        'code' => true,
        'file' => false,
        'line' => false,
        'trace' => false,
    ];

    $builder = new ContextBuilder(config: $config);
    $exception = new \InvalidArgumentException('test', 100);

    $context = $builder->build(exception: $exception);

    expect($context)->toBe([
        'exception' => \InvalidArgumentException::class,
        'code' => 100,
    ]);
});

it('handles missing config keys', function (): void {
    $config = [
        'exception' => true,
    ];

    $builder = new ContextBuilder(config: $config);
    $exception = new \RuntimeException('test');

    $context = $builder->build(exception: $exception);

    expect($context)->toBe([
        'exception' => \RuntimeException::class,
    ]);
});
