<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Console\Commands;

use Jiordiviera\PhpUi\Console\Logo;
use Jiordiviera\PhpUi\Core\Transformer\CssInjector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\note;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;

class InitCommand extends Command
{
    protected static $defaultName = 'init';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        Logo::render();

        $detector = new \Jiordiviera\PhpUi\Core\Detector\ProjectDetector;

        // 1. Analyse avec Loader
        $analysis = spin(
            function () use ($detector) {
                sleep(1); // Fake delay for UX

                return [
                    'version' => $detector->detectTailwindVersion(),
                    'namespace' => $detector->getRootNamespace(),
                    'root' => $detector->getProjectRoot(),
                ];
            },
            'Analyzing project structure...'
        );

        $detectedVersion = $analysis['version'];
        $detectedNamespace = $analysis['namespace'];
        $projectPath = $analysis['root'];

        note("Detected: Tailwind {$detectedVersion}, Namespace: {$detectedNamespace}");

        // 2. Configuration
        $version = select(
            label: 'Which version of Tailwind do you use?',
            options: ['v3', 'v4'],
            default: 'v4'
        );

        $baseColor = select(
            label: 'Which color would you like to use as base color?',
            options: [
                'zinc' => 'Zinc',
                'slate' => 'Slate',
                'stone' => 'Stone',
                'gray' => 'Gray',
                'neutral' => 'Neutral',
            ],
            default: 'zinc'
        );

        $accentColor = select(
            label: 'Which color would you like to use as primary color?',
            options: [
                'red' => 'Red',
                'rose' => 'Rose',
                'orange' => 'Orange',
                'amber' => 'Amber',
                'yellow' => 'Yellow',
                'lime' => 'Lime',
                'green' => 'Green',
                'emerald' => 'Emerald',
                'teal' => 'Teal',
                'cyan' => 'Cyan',
                'sky' => 'Sky',
                'blue' => 'Blue',
                'indigo' => 'Indigo',
                'violet' => 'Violet',
                'purple' => 'Purple',
                'fuchsia' => 'Fuchsia',
                'pink' => 'Pink',
            ],
            default: 'blue'
        );

        $config = [
            'tailwind' => $version,
            'theme' => [
                'base' => $baseColor,
                'accent' => $accentColor,
            ],
            'paths' => [
                'components' => 'app/Livewire/UI',
                'views' => 'resources/views/components/ui',
            ],
            'namespace' => $detectedNamespace.'Livewire\\UI',
        ];

        // 3. Creation
        spin(
            fn () => file_put_contents(
                $projectPath.'/php-ui.json',
                json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            ),
            'Generating configuration file...'
        );

        // 4. Initial CSS Injection (for v4)
        if ($version === 'v4') {
            $cssPath = $projectPath.'/resources/css/app.css';
            if (file_exists($cssPath)) {
                $injector = new CssInjector;
                $themeVars = $injector->generateThemeVariables($baseColor, $accentColor);
                spin(fn () => $injector->injectCompleteColorScale($cssPath, $baseColor, $accentColor), 'Injecting complete color scale...');
                spin(fn () => $injector->injectVars($cssPath, $themeVars), 'Injecting theme variables...');
            }
        }

        outro("✅ PHP-UI is ready! {$baseColor} + {$accentColor} theme configured.");

        return Command::SUCCESS;
    }
}
