<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Core\Transformer;

class StubTransformer
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function transform(string $content, string $componentName): string
    {
        $replacements = [
            '{{ namespace }}' => $this->config['namespace'],
            '{{ class }}' => ucfirst($componentName),
            '{{ view }}' => 'livewire.ui.'.strtolower($componentName),
            '{{ componentName }}' => strtolower($componentName),
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $content
        );
    }
}
