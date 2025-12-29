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
    $registry = new RemoteRegistry;
    $result = $registry->fetchFromUrl('https://invalid-url-that-does-not-exist.example.com/component.stub');

    expect($result)->toBeNull();
});

test('it returns empty array for invalid registry', function () {
    $registry = new RemoteRegistry;
    $result = $registry->listFromRegistry('https://invalid-url-that-does-not-exist.example.com/registry.json');

    expect($result)->toBeArray()->toBeEmpty();
});

test('it parses github repo format with branch', function () {
    $registry = new RemoteRegistry;

    // Test that it handles repo@branch format (will fail to fetch but shouldn't error)
    $result = $registry->fetchFromGitHub('button', 'nonexistent/repo@develop');

    expect($result)->toBeNull(); // Expected since repo doesn't exist
});
