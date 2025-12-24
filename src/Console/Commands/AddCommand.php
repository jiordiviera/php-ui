<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Jiordiviera\PhpUi\Console\Logo;
use Jiordiviera\PhpUi\Core\ComponentManifest;
use Jiordiviera\PhpUi\Core\Detector\ProjectDetector;
use Jiordiviera\PhpUi\Core\Transformer\CssInjector;
use Jiordiviera\PhpUi\Core\Transformer\DependencyManager;
use Jiordiviera\PhpUi\Core\Transformer\StubTransformer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\note;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\search;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\warning;

class AddCommand extends Command
{
    protected static $defaultName = 'add';

    protected function configure()
    {
        $this->addArgument('component', InputArgument::OPTIONAL, 'The name of the component');
        $this->addOption('force', 'f', InputOption::VALUE_NONE, 'Force overwrite of existing files');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('component');

        if (! $name) {
            Logo::render();
            $name = search(
                label: 'Search for a component to add',
                options: fn(string $value) => ComponentManifest::all()
                    ->filter(fn($manifest, $key) => strlen($value) === 0 || str_contains($key, $value))
                    ->mapWithKeys(fn($manifest, $key) => [$key => $key]) // ou [$key => $manifest['description'] ?? $key]
                    ->toArray(),
                placeholder: 'Type to search...'
            );

            if (! $name) {
                error('No component selected.');

                return Command::FAILURE;
            }
        } else {
            Logo::render();
        }

        $force = $input->getOption('force');
        info("Installing component: <comment>{$name}</comment>");
        $detector = new ProjectDetector;
        $projectPath = $detector->getProjectRoot();
        $configPath = $projectPath . '/php-ui.json';

        if (! file_exists($configPath)) {
            error("No php-ui.json file found at {$projectPath}. Please run 'php-ui init' first.");

            return Command::FAILURE;
        }

        $filesystem = new Filesystem;
        $config = json_decode(file_get_contents($configPath), true);
        $manifest = ComponentManifest::get($name);

        // 1. Install Dependencies
        $dependencyManager = new DependencyManager;
        if ($manifest && ! empty($manifest['dependencies'])) {
            if (! empty($manifest['dependencies']['composer'])) {
                $dependencyManager->checkAndInstall($manifest['dependencies']['composer'], 'composer');
            }
            if (! empty($manifest['dependencies']['npm'])) {
                $dependencyManager->checkAndInstall($manifest['dependencies']['npm'], 'npm');
            }
        }

        // 2. Transform and Create Files
        $transformer = new StubTransformer($config);
        $createdFiles = [];

        // Blade Files
        if ($manifest && ! empty($manifest['files'])) {
            foreach ($manifest['files'] as $stubName => $targetName) {
                $bladeStub = __DIR__ . "/../../../stubs/{$stubName}";
                $bladeTarget = $projectPath . '/' . $config['paths']['views'] . '/' . $targetName;

                if ($filesystem->exists($bladeStub)) {
                    $content = $transformer->transform($filesystem->get($bladeStub), $name);
                    if ($this->writeFile($filesystem, $bladeTarget, $content, $force)) {
                        $createdFiles[] = $config['paths']['views'] . '/' . $targetName;
                    }
                }
            }
        } else {
            // Default single-file
            $bladeStub = __DIR__ . "/../../../stubs/{$name}.blade.php.stub";
            $bladeTarget = $projectPath . '/' . $config['paths']['views'] . '/' . strtolower($name) . '.blade.php';

            if ($filesystem->exists($bladeStub)) {
                $content = $transformer->transform($filesystem->get($bladeStub), $name);
                if ($this->writeFile($filesystem, $bladeTarget, $content, $force)) {
                    $createdFiles[] = $config['paths']['views'] . '/' . strtolower($name) . '.blade.php';
                }
            }
        }

        // PHP Class (Optional)
        $phpStub = __DIR__ . "/../../../stubs/{$name}.php.stub";
        if ($filesystem->exists($phpStub)) {
            $phpTarget = $projectPath . '/' . $config['paths']['components'] . '/' . ucfirst($name) . '.php';
            $content = $transformer->transform($filesystem->get($phpStub), $name);
            if ($this->writeFile($filesystem, $phpTarget, $content, $force)) {
                $createdFiles[] = $config['paths']['components'] . '/' . ucfirst($name) . '.php';
            }
        }

        // JS Stubs
        if ($manifest && ! empty($manifest['js_stubs'])) {
            $jsDir = $projectPath . '/resources/js/ui';
            $filesystem->ensureDirectoryExists($jsDir);

            foreach ($manifest['js_stubs'] as $jsStubName) {
                $jsStubPath = __DIR__ . "/../../../stubs/{$jsStubName}.stub";
                $jsTarget = $jsDir . '/' . $jsStubName;

                if ($filesystem->exists($jsStubPath)) {
                    $content = $transformer->transform($filesystem->get($jsStubPath), $name);
                    if ($this->writeFile($filesystem, $jsTarget, $content, $force)) {
                        $createdFiles[] = 'resources/js/ui/' . $jsStubName;
                    }
                }
            }
        }

        // 3. Inject CSS Variables
        $cssInjected = false;
        if ($manifest && ! empty($manifest['css_vars'])) {
            $cssPath = $projectPath . '/resources/css/app.css';
            if ($filesystem->exists($cssPath)) {
                $injector = new CssInjector;
                $isV4 = ($config['tailwind'] ?? 'v3') === 'v4';

                if ($isV4) {
                    spin(fn() => $injector->injectVars($cssPath, $manifest['css_vars']), 'Injecting CSS variables...');
                    $cssInjected = true;
                }
            }
        }

        // Summary
        if (empty($createdFiles)) {
            error("Could not generate files for component: {$name}. Check if stubs exist or if operation was cancelled.");

            return Command::FAILURE;
        }

        $summary = "Created files:\n";
        foreach ($createdFiles as $file) {
            $summary .= "- <comment>{$file}</comment>\n";
        }

        if ($cssInjected) {
            $summary .= "- <info>CSS variables injected into app.css</info>\n";
        } elseif ($manifest && ! empty($manifest['css_vars']) && ! ($isV4 ?? false)) {
            note(
                "Tailwind v3 detected. Please add these variables to your CSS manually:\n" .
                    implode("\n", array_map(fn($k, $v) => "$k: $v;", array_keys($manifest['css_vars']), $manifest['css_vars']))
            );
        }

        // JS Warnings
        if ($manifest && ! empty($manifest['js_stubs'])) {
            foreach ($manifest['js_stubs'] as $jsStubName) {
                $relativeJsPath = './ui/' . str_replace('.js', '', $jsStubName);
                warning("ACTION REQUIRED: Add 'import '{$relativeJsPath}';' to your resources/js/app.js");
            }
        }

        note($summary);
        outro("✅ Component {$name} added successfully!");

        return Command::SUCCESS;
    }

    protected function writeFile(Filesystem $filesystem, string $path, string $content, bool $force): bool
    {
        if ($filesystem->exists($path) && ! $force) {
            if (! confirm("File [{$path}] already exists. Overwrite?", default: false)) {
                return false;
            }
        }

        $filesystem->ensureDirectoryExists(dirname($path));
        $filesystem->put($path, $content);

        return true;
    }
}
