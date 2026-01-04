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
            default: $detectedVersion
        );

        $baseColor = select(
            label: 'Select a base color for your UI',
            options: [
                'slate' => 'Slate (Cool)',
                'zinc' => 'Zinc (Balanced)',
                'gray' => 'Gray (Neutral)',
                'neutral' => 'Neutral (Warm)',
                'stone' => 'Stone (Earth)',
            ],
            default: 'slate'
        );

        $accentColor = select(
            label: 'Select your primary accent color',
            options: [
                'blue' => 'Blue',
                'indigo' => 'Indigo',
                'violet' => 'Violet',
                'rose' => 'Rose',
                'orange' => 'Orange',
                'emerald' => 'Emerald',
            ],
            default: 'indigo'
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
                $vars = [
                    '--primary' => "var(--color-{$accentColor}-600)",
                    '--primary-foreground' => '#ffffff',
                    '--base' => "var(--color-{$baseColor}-950)",
                ];
                spin(fn () => $injector->injectVars($cssPath, $vars), 'Injecting theme variables...');
            }
        }

        outro('✅ PHP-UI is ready! Theme set to '.ucfirst($accentColor).'.');

        return Command::SUCCESS;
    }
}
