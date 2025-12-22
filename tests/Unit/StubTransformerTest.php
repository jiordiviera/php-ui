<?php

use Jiordiviera\PhpUi\Core\Transformer\StubTransformer;

test('it transforms placeholders in stub content', function () {
    $config = [
        'namespace' => 'App\\Livewire\\UI',
        'paths' => [
            'views' => 'resources/views/components/ui',
        ],
    ];

    $transformer = new StubTransformer($config);
    $content = 'namespace {{ namespace }}; class {{ class }} { public $view = "{{ view }}"; }';

    $transformed = $transformer->transform($content, 'button');

    expect($transformed)->toContain('namespace App\\Livewire\\UI;')
        ->and($transformed)->toContain('class Button')
        ->and($transformed)->toContain('livewire.ui.button');
});
