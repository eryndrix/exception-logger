<?php declare(strict_types=1);

use Ekara\Logging\ContextBuilder;

it('includes trace when enabled', function (): void {
    $config = [
        'exception' => false,
        'code' => false,
        'file' => false,
        'line' => false,
        'trace' => true,
    ];

    $builder = new ContextBuilder(config: $config);
    $exception = new \RuntimeException('test');

    $context = $builder->build(exception: $exception);

    expect($context)->toBe(['trace' => $exception->getTraceAsString()]);
});

it('trace is string', function (): void {
    $config = [
        'exception' => false,
        'code' => false,
        'file' => false,
        'line' => false,
        'trace' => true,
    ];

    $builder = new ContextBuilder(config: $config);
    $exception = new \RuntimeException('test');

    $context = $builder->build(exception: $exception);

    expect($context['trace'])->toBeString();
});
