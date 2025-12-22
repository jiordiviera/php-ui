<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Laravel\Prompts\text;
use function Laravel\Prompts\select;
use function Laravel\Prompts\info;

class InitCommand extends Command
{
    protected static $defaultName = 'init';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $detector = new \Jiordiviera\PhpUi\Core\Detector\ProjectDetector();

        info('🚀 Welcome to PHP-UI');

        $detectedVersion = $detector->detectTailwindVersion();
        $detectedNamespace = $detector->getRootNamespace();

        $version = select(
            label: 'Which version of Tailwind do you use?',
            options: ['v3', 'v4'],
            default: $detectedVersion
        );

        $config = [
            'tailwind' => $version,
            'paths' => [
                'components' => 'app/Livewire/UI', // Keep as fallback if needed, but not asked
                'views' => 'resources/views/components/ui',
            ],
            'namespace' => $detectedNamespace . 'Livewire\\UI',
        ];

        file_put_contents(
            'php-ui.json',
            json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
        );

        info('✅ php-ui.json file created successfully!');

        return Command::SUCCESS;
    }
}
