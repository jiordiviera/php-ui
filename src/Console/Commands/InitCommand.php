<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\select;
use function Laravel\Prompts\note;

class InitCommand extends Command
{
    protected static $defaultName = 'init';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        intro('🚀 Welcome to PHP-UI Installer');

        $detector = new \Jiordiviera\PhpUi\Core\Detector\ProjectDetector();

        // 1. Analyse avec Loader
        $analysis = spin(
            function () use ($detector) {
                sleep(1); // Fake delay for UX (user feels "work" is being done)
                return [
                    'version' => $detector->detectTailwindVersion(),
                    'namespace' => $detector->getRootNamespace(),
                ];
            },
            'Analyzing project structure...'
        );

        $detectedVersion = $analysis['version'];
        $detectedNamespace = $analysis['namespace'];

        note("Detected: Tailwind {$detectedVersion}, Namespace: {$detectedNamespace}");

        // 2. Configuration (Smart Defaults)
        // If detected is v4, we assume it's correct but let user override if they want
        // Actually, let's keep the select for control but use detection as default
        $version = select(
            label: 'Which version of Tailwind do you use?',
            options: ['v3', 'v4'],
            default: $detectedVersion
        );

        $config = [
            'tailwind' => $version,
            'paths' => [
                'components' => 'app/Livewire/UI',
                'views' => 'resources/views/components/ui',
            ],
            'namespace' => $detectedNamespace . 'Livewire\\UI',
        ];

        // 3. Creation with Loader
        spin(
            fn() => file_put_contents(
                'php-ui.json',
                json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            ),
            'Generating configuration file...'
        );

        outro('✅ PHP-UI is ready! You can now run "php-ui add <component>".');

        return Command::SUCCESS;
    }
}
