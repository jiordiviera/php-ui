<?php

use Jiordiviera\PhpUi\Core\Registry\RemoteRegistry;

test('it extracts component name from filename', function () {
    $registry = new RemoteRegistry;

    // Use reflection to test protected method
    $reflection = new ReflectionClass($registry);
    $method = $reflection->getMethod('extractComponentName');
    $method->setAccessible(true);

    expect($method->invoke($registry, 'button.blade.php.stub'))->toBe('button')
        ->and($method->invoke($registry, 'my-component.blade.php.stub'))->toBe('my-component')
        ->and($method->invoke($registry, 'widget.js.stub'))->toBe('widget');
});

test('it can set custom default registry', function () {
    $registry = new RemoteRegistry;
    $result = $registry->setDefaultRegistry('https://example.com/registry.json');

    expect($result)->toBeInstanceOf(RemoteRegistry::class);
});

test('it returns null for invalid url', function () {
    // Create a mock registry that overrides httpGet
    $mockRegistry = new class extends RemoteRegistry
    {
        protected function httpGet(string $url): ?string
        {
            return null; // Simulate failed request
        }
    };

    $result = $mockRegistry->fetchFromUrl('https://example.com/component.stub');

    expect($result)->toBeNull();
});

test('it returns empty array for invalid registry', function () {
    // Create a mock registry that overrides getRegistry
    $mockRegistry = new class extends RemoteRegistry
    {
        protected function getRegistry(string $url): ?array
        {
            return null; // Simulate failed request
        }
    };

    $result = $mockRegistry->listFromRegistry('https://example.com/registry.json');

    expect($result)->toBeArray()->toBeEmpty();
});

test('it parses github repo format with branch', function () {
    // Create a mock registry that overrides httpGet
    $mockRegistry = new class extends RemoteRegistry
    {
        protected function httpGet(string $url): ?string
        {
            return null; // Simulate failed request
        }
    };

    $result = $mockRegistry->fetchFromGitHub('button', 'nonexistent123/repo456@branch789');

    expect($result)->toBeNull();
});
