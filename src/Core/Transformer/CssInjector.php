<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Core\Transformer;

use Illuminate\Filesystem\Filesystem;

class CssInjector
{
    protected Filesystem $files;

    public function __construct()
    {
        $this->files = new Filesystem;
    }

    public function injectVars(string $path, array $vars): void
    {
        if (! $this->files->exists($path)) {
            return;
        }

        $content = $this->files->get($path);

        if (! str_contains($content, '@theme')) {
            $content .= "\n@theme {\n}\n";
        }

        foreach ($vars as $name => $value) {
            if (! str_contains($content, $name)) {
                $content = str_replace('@theme {', "@theme {\n  {$name}: {$value};", $content);
            }
        }

        $this->files->put($path, $content);
    }
}
