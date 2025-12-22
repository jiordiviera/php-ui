<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Core\Detector;

use Illuminate\Filesystem\Filesystem;

class ProjectDetector
{
    protected Filesystem $files;

    public function __construct(?Filesystem $files = null)
    {
        $this->files = $files ?? new Filesystem;
    }

    public function detectTailwindVersion(): string
    {
        if ($this->files->exists('package.json')) {
            $package = json_decode($this->files->get('package.json'), true);
            $deps = array_merge($package['dependencies'] ?? [], $package['devDependencies'] ?? []);

            if (isset($deps['tailwindcss'])) {
                return str_starts_with($deps['tailwindcss'], '^4') ? 'v4' : 'v3';
            }
        }

        if ($this->files->exists('resources/css/app.css')) {
            $content = $this->files->get('resources/css/app.css');

            return str_contains($content, '@theme') ? 'v4' : 'v3';
        }

        return 'v4';
    }

    public function getRootNamespace(): string
    {
        $root = $this->getProjectRoot();
        $composerPath = $root.'/composer.json';

        if ($this->files->exists($composerPath)) {
            $composer = json_decode($this->files->get($composerPath), true);

            return key($composer['autoload']['psr-4'] ?? ['App\\' => 'app/']) ?: 'App\\';
        }

        return 'App\\';
    }

    public function getProjectRoot(): string
    {
        $dir = getcwd();

        if ($dir === false) {
            return '.';
        }

        $lastDir = null;

        while ($dir !== '/' && $dir !== '.' && $dir !== $lastDir) {
            if ($this->files->exists($dir.'/composer.json')) {
                return $dir;
            }
            $lastDir = $dir;
            $dir = dirname($dir);
        }

        return getcwd() ?: '.';
    }
}
