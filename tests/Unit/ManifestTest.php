<?php

use Jiordiviera\PhpUi\Core\ComponentManifest;

test('it can retrieve component configuration', function () {
    $config = ComponentManifest::get('button');

    expect($config)->toBeArray()
        ->toHaveKey('css_vars')
        ->and($config['css_vars'])->toHaveKey('--ui-primary');
});

test('it returns null for unknown component', function () {
    $config = ComponentManifest::get('non-existent');

    expect($config)->toBeNull();
});
