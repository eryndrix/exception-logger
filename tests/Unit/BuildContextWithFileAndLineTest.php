<?php declare(strict_types=1);

use Eryndrix\Logging\ContextBuilder;

it('includes file and line when enabled', function (): void {
    $config = [
        'exception' => false,
        'code' => false,
        'file' => true,
        'line' => true,
        'trace' => false,
    ];

    $builder = new ContextBuilder(config: $config);
    $exception = new \RuntimeException('test');

    $context = $builder->build(exception: $exception);

    expect($context)->toHaveKeys(['file', 'line']);
    expect($context['file'])->toBe($exception->getFile());
    expect($context['line'])->toBe($exception->getLine());
});
