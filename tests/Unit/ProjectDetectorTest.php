<?php

use Illuminate\Filesystem\Filesystem;
use Jiordiviera\PhpUi\Core\Detector\ProjectDetector;

test('it detects tailwind v4 from package.json', function () {
    $fs = $this->createMock(Filesystem::class);
    $fs->method('exists')->with('package.json')->willReturn(true);
    $fs->method('get')->with('package.json')->willReturn(json_encode([
        'dependencies' => ['tailwindcss' => '^4.0.0'],
    ]));

    $detector = new ProjectDetector($fs);
    expect($detector->detectTailwindVersion())->toBe('v4');
});

test('it detects tailwind v3 from package.json', function () {
    $fs = $this->createMock(Filesystem::class);
    $fs->method('exists')->with('package.json')->willReturn(true);
    $fs->method('get')->with('package.json')->willReturn(json_encode([
        'dependencies' => ['tailwindcss' => '^3.4.0'],
    ]));

    $detector = new ProjectDetector($fs);
    expect($detector->detectTailwindVersion())->toBe('v3');
});

test('it detects namespace from composer.json', function () {
    $fs = $this->createMock(Filesystem::class);
    // Root detection
    $fs->method('exists')->willReturnCallback(fn ($path) => str_contains($path, 'composer.json'));
    $fs->method('get')->willReturnCallback(fn ($path) => json_encode([
        'autoload' => ['psr-4' => ['MyNamespace\\' => 'src/']],
    ]));

    $detector = new ProjectDetector($fs);
    expect($detector->getRootNamespace())->toBe('MyNamespace\\');
});
