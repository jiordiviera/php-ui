<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Core\Detector;

class ProjectDetector
{
    public function detectTailwindVersion(): string
    {
        if (file_exists('package.json')) {
            $package = json_decode(file_get_contents('package.json'), true);
            $deps = array_merge($package['dependencies'] ?? [], $package['devDependencies'] ?? []);
            
            if (isset($deps['tailwindcss'])) {
                return str_starts_with($deps['tailwindcss'], '^4') ? 'v4' : 'v3';
            }
        }

        if (file_exists('resources/css/app.css')) {
            $content = file_get_contents('resources/css/app.css');
            return str_contains($content, '@theme') ? 'v4' : 'v3';
        }

        return 'v4';
    }

    public function getRootNamespace(): string
    {
        $root = $this->getProjectRoot();
        $composerPath = $root . '/composer.json';
        
        if (file_exists($composerPath)) {
            $composer = json_decode(file_get_contents($composerPath), true);
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
            if (file_exists($dir . '/composer.json')) {
                return $dir;
            }
            $lastDir = $dir;
            $dir = dirname($dir);
        }

        return getcwd() ?: '.';
    }
}