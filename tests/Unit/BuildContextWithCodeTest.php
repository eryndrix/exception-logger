<?php declare(strict_types=1);

use Ekara\Logging\ContextBuilder;

it('includes code when enabled', function (): void {
    $config = [
        'exception' => false,
        'code' => true,
        'file' => false,
        'line' => false,
        'trace' => false,
    ];

    $builder = new ContextBuilder(config: $config);
    $exception = new \RuntimeException('test', 42);

    $context = $builder->build(exception: $exception);

    expect($context)->toBe(['code' => 42]);
});
