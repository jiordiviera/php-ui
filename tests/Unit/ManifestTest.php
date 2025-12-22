<?php

use Jiordiviera\PhpUi\Core\ComponentManifest;

test('it can retrieve component configuration', function () {
    $config = ComponentManifest::get('button');

    expect($config)->toBeArray()
        ->toHaveKey('description')
        ->toHaveKey('dependencies')
        ->and($config['dependencies']['composer'])->toContain('mallardduck/blade-lucide-icons');
});

test('it returns null for unknown component', function () {
    $config = ComponentManifest::get('non-existent');

    expect($config)->toBeNull();
});
