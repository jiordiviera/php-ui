<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Core\Transformer;

use Jiordiviera\PhpUi\Core\Detector\ProjectDetector;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\spin;

class DependencyManager
{
    public function checkAndInstall(array $packages, string $type = 'composer', bool $force = false): void
    {
        if (getenv('PHP_UI_TESTING')) {
            return;
        }

        $detector = new ProjectDetector;
        $root = $detector->getProjectRoot();

        if ($type === 'composer') {
            $this->installComposerPackages($packages, $root, $force);
        } elseif ($type === 'npm') {
            $this->installNpmPackages($packages, $root, $force);
        }
    }

    protected function installComposerPackages(array $packages, string $root, bool $force = false): void
    {
        $composerPath = $root.'/composer.json';
        if (! file_exists($composerPath)) {
            return;
        }

        $composer = json_decode(file_get_contents($composerPath), true);
        $installed = array_merge($composer['require'] ?? [], $composer['require-dev'] ?? []);

        foreach ($packages as $package) {
            if (! isset($installed[$package])) {
                if ($force || confirm("The component requires composer package {$package}. Install it?")) {
                    spin(
                        fn () => shell_exec("composer require {$package} --working-dir=\"{$root}\""),
                        "Installing {$package}..."
                    );
                }
            }
        }
    }

    protected function installNpmPackages(array $packages, string $root, bool $force = false): void
    {
        $packagePath = $root.'/package.json';
        if (! file_exists($packagePath)) {
            return;
        }

        $packageJson = json_decode(file_get_contents($packagePath), true);
        $installed = array_merge($packageJson['dependencies'] ?? [], $packageJson['devDependencies'] ?? []);

        foreach ($packages as $package) {
            if (! isset($installed[$package])) {
                if ($force || confirm("The component requires npm package {$package}. Install it?")) {
                    // Try to detect the package manager (npm, yarn, pnpm, bun)
                    $cmd = 'npm install';
                    if (file_exists($root.'/bun.lockb') || file_exists($root.'/bun.lock')) {
                        $cmd = 'bun add';
                    } elseif (file_exists($root.'/pnpm-lock.yaml')) {
                        $cmd = 'pnpm add';
                    } elseif (file_exists($root.'/yarn.lock')) {
                        $cmd = 'yarn add';
                    }

                    spin(
                        fn () => shell_exec("cd \"{$root}\" && {$cmd} {$package}"),
                        "Installing {$package} via {$cmd}..."
                    );
                }
            }
        }
    }
}
