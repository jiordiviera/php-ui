<?php

use Jiordiviera\PhpUi\Core\Transformer\StubTransformer;

test('it transforms blade component placeholders', function () {
    $config = [];

    $transformer = new StubTransformer($config);
    $content = '<div class="{{ componentName }}-container">{{ componentName }}</div>';

    $transformed = $transformer->transform($content, 'button');

    expect($transformed)->toContain('button-container')
        ->and($transformed)->toContain('button');
});
