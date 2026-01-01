<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Jiordiviera\PhpUi\Console\Logo;
use Jiordiviera\PhpUi\Core\ComponentManifest;
use Jiordiviera\PhpUi\Core\Detector\ProjectDetector;
use Jiordiviera\PhpUi\Core\Registry\RemoteRegistry;
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
        $this->addOption('url', 'u', InputOption::VALUE_REQUIRED, 'Install component from a direct URL');
        $this->addOption('registry', 'r', InputOption::VALUE_REQUIRED, 'Use a custom registry URL');
        $this->addOption('repo', null, InputOption::VALUE_REQUIRED, 'Install from a GitHub repository (format: owner/repo or owner/repo@branch)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        Logo::render();

        $name = $input->getArgument('component');
        $force = $input->getOption('force');
        $url = $input->getOption('url');
        $registryUrl = $input->getOption('registry');
        $repo = $input->getOption('repo');

        $detector = new ProjectDetector;
        $projectPath = $detector->getProjectRoot();
        $configPath = $projectPath.'/php-ui.json';

        if (! file_exists($configPath)) {
            error("No php-ui.json file found at {$projectPath}. Please run 'php-ui init' first.");

            return Command::FAILURE;
        }

        $filesystem = new Filesystem;
        $config = json_decode(file_get_contents($configPath), true);

        // Handle remote sources (URL, registry, or GitHub repo)
        if ($url || $registryUrl || $repo) {
            return $this->installFromRemote($input, $output, $config, $projectPath, $filesystem, $force);
        }

        // Local installation (existing behavior)
        if (! $name) {
            $name = search(
                label: 'Search for a component to add',
                options: fn (string $value) => ComponentManifest::all()
                    ->filter(fn ($manifest, $key) => strlen($value) === 0 || str_contains($key, $value))
                    ->mapWithKeys(fn ($manifest, $key) => [$key => $key])
                    ->toArray(),
                placeholder: 'Type to search...'
            );

            if (! $name) {
                error('No component selected.');

                return Command::FAILURE;
            }
        }

        info("Installing component: <comment>{$name}</comment>");

        $manifest = ComponentManifest::get($name);

        // 1. Install Dependencies
        $dependencyManager = new DependencyManager;
        if ($manifest && ! empty($manifest['dependencies'])) {
            if (! empty($manifest['dependencies']['composer'])) {
                $dependencyManager->checkAndInstall($manifest['dependencies']['composer'], 'composer', $force);
            }
            if (! empty($manifest['dependencies']['npm'])) {
                $dependencyManager->checkAndInstall($manifest['dependencies']['npm'], 'npm', $force);
            }
        }

        // 2. Transform and Create Files
        $transformer = new StubTransformer($config);
        $createdFiles = [];

        // Blade Files
        if ($manifest && ! empty($manifest['files'])) {
            foreach ($manifest['files'] as $stubName => $targetName) {
                $bladeStub = __DIR__."/../../../stubs/{$stubName}";
                $bladeTarget = $projectPath.'/'.$config['paths']['views'].'/'.$targetName;

                if ($filesystem->exists($bladeStub)) {
                    $content = $transformer->transform($filesystem->get($bladeStub), $name);
                    if ($this->writeFile($filesystem, $bladeTarget, $content, $force)) {
                        $createdFiles[] = $config['paths']['views'].'/'.$targetName;
                    }
                }
            }
        } else {
            // Default single-file
            $bladeStub = __DIR__."/../../../stubs/{$name}.blade.php.stub";
            $bladeTarget = $projectPath.'/'.$config['paths']['views'].'/'.strtolower($name).'.blade.php';

            if ($filesystem->exists($bladeStub)) {
                $content = $transformer->transform($filesystem->get($bladeStub), $name);
                if ($this->writeFile($filesystem, $bladeTarget, $content, $force)) {
                    $createdFiles[] = $config['paths']['views'].'/'.strtolower($name).'.blade.php';
                }
            }
        }

        // PHP Class (Optional)
        $phpStub = __DIR__."/../../../stubs/{$name}.php.stub";
        if ($filesystem->exists($phpStub)) {
            $phpTarget = $projectPath.'/'.$config['paths']['components'].'/'.ucfirst($name).'.php';
            $content = $transformer->transform($filesystem->get($phpStub), $name);
            if ($this->writeFile($filesystem, $phpTarget, $content, $force)) {
                $createdFiles[] = $config['paths']['components'].'/'.ucfirst($name).'.php';
            }
        }

        // JS Stubs
        if ($manifest && ! empty($manifest['js_stubs'])) {
            $jsDir = $projectPath.'/resources/js/ui';
            $filesystem->ensureDirectoryExists($jsDir);

            foreach ($manifest['js_stubs'] as $jsStubName) {
                $jsStubPath = __DIR__."/../../../stubs/{$jsStubName}.stub";
                $jsTarget = $jsDir.'/'.$jsStubName;

                if ($filesystem->exists($jsStubPath)) {
                    $content = $transformer->transform($filesystem->get($jsStubPath), $name);
                    if ($this->writeFile($filesystem, $jsTarget, $content, $force)) {
                        $createdFiles[] = 'resources/js/ui/'.$jsStubName;
                    }
                }
            }
        }

        // 3. Inject CSS Variables
        $cssInjected = false;
        if ($manifest && ! empty($manifest['css_vars'])) {
            $cssPath = $projectPath.'/resources/css/app.css';
            if ($filesystem->exists($cssPath)) {
                $injector = new CssInjector;
                $isV4 = ($config['tailwind'] ?? 'v3') === 'v4';

                if ($isV4) {
                    spin(fn () => $injector->injectVars($cssPath, $manifest['css_vars']), 'Injecting CSS variables...');
                    $cssInjected = true;
                }
            }
        }

        // Summary
        if (empty($createdFiles)) {
            error("Could not generate files for component: {$name}. Check if stubs exist or if operation was cancelled.");

            return Command::FAILURE;
        }

        $this->showSummary($createdFiles, $cssInjected, $manifest, $config);
        outro("✅ Component {$name} added successfully!");

        return Command::SUCCESS;
    }

    /**
     * Install component from remote source (URL, registry, or GitHub).
     */
    protected function installFromRemote(
        InputInterface $input,
        OutputInterface $output,
        array $config,
        string $projectPath,
        Filesystem $filesystem,
        bool $force
    ): int {
        $name = $input->getArgument('component');
        $url = $input->getOption('url');
        $registryUrl = $input->getOption('registry');
        $repo = $input->getOption('repo');

        $registry = new RemoteRegistry;
        $remoteComponent = null;

        // Fetch from direct URL
        if ($url) {
            info("Fetching component from URL: <comment>{$url}</comment>");
            $remoteComponent = spin(
                fn () => $registry->fetchFromUrl($url),
                'Downloading component...'
            );

            if (! $remoteComponent) {
                error("Failed to fetch component from URL: {$url}");

                return Command::FAILURE;
            }

            $name = $remoteComponent['name'];
        }

        // Fetch from GitHub repository
        if ($repo) {
            if (! $name) {
                error('Component name is required when using --repo. Usage: php-ui add button --repo owner/repo');

                return Command::FAILURE;
            }

            info("Fetching component <comment>{$name}</comment> from GitHub: <comment>{$repo}</comment>");
            $remoteComponent = spin(
                fn () => $registry->fetchFromGitHub($name, $repo),
                'Downloading from GitHub...'
            );

            if (! $remoteComponent) {
                error("Failed to fetch component '{$name}' from repository: {$repo}");

                return Command::FAILURE;
            }
        }

        // Fetch from custom registry
        if ($registryUrl && ! $url && ! $repo) {
            if (! $name) {
                // List available components from registry
                info("Fetching components from registry: <comment>{$registryUrl}</comment>");
                $components = spin(
                    fn () => $registry->listFromRegistry($registryUrl),
                    'Loading registry...'
                );

                if (empty($components)) {
                    error("No components found in registry: {$registryUrl}");

                    return Command::FAILURE;
                }

                $name = search(
                    label: 'Search for a component to add',
                    options: fn (string $value) => collect($components)
                        ->filter(fn ($desc, $key) => strlen($value) === 0 || str_contains($key, $value))
                        ->toArray(),
                    placeholder: 'Type to search...'
                );

                if (! $name) {
                    error('No component selected.');

                    return Command::FAILURE;
                }
            }

            info("Fetching component <comment>{$name}</comment> from registry");
            $remoteComponent = spin(
                fn () => $registry->fetchFromRegistry($name, $registryUrl),
                'Downloading component...'
            );

            if (! $remoteComponent) {
                error("Failed to fetch component '{$name}' from registry: {$registryUrl}");

                return Command::FAILURE;
            }
        }

        if (! $remoteComponent) {
            error('No remote component to install.');

            return Command::FAILURE;
        }

        // Install dependencies
        $dependencyManager = new DependencyManager;
        if (! empty($remoteComponent['dependencies'])) {
            if (! empty($remoteComponent['dependencies']['composer'])) {
                $dependencyManager->checkAndInstall($remoteComponent['dependencies']['composer'], 'composer', $force);
            }
            if (! empty($remoteComponent['dependencies']['npm'])) {
                $dependencyManager->checkAndInstall($remoteComponent['dependencies']['npm'], 'npm', $force);
            }
        }

        // Transform and write files
        $transformer = new StubTransformer($config);
        $createdFiles = [];

        // Handle blade content from URL
        if (isset($remoteComponent['files']['blade'])) {
            $bladeTarget = $projectPath.'/'.$config['paths']['views'].'/'.strtolower($name).'.blade.php';
            $content = $transformer->transform($remoteComponent['files']['blade'], $name);

            if ($this->writeFile($filesystem, $bladeTarget, $content, $force)) {
                $createdFiles[] = $config['paths']['views'].'/'.strtolower($name).'.blade.php';
            }
        }

        // Handle files from registry
        foreach ($remoteComponent['files'] ?? [] as $stubName => $fileData) {
            if ($stubName === 'blade') {
                continue;
            }

            if (is_array($fileData) && isset($fileData['content'])) {
                $bladeTarget = $projectPath.'/'.$config['paths']['views'].'/'.$fileData['target'];
                $content = $transformer->transform($fileData['content'], $name);

                if ($this->writeFile($filesystem, $bladeTarget, $content, $force)) {
                    $createdFiles[] = $config['paths']['views'].'/'.$fileData['target'];
                }
            }
        }

        // Handle JS stubs
        if (! empty($remoteComponent['js_stubs'])) {
            $jsDir = $projectPath.'/resources/js/ui';
            $filesystem->ensureDirectoryExists($jsDir);

            foreach ($remoteComponent['js_stubs'] as $jsStubName => $jsContent) {
                $jsTarget = $jsDir.'/'.$jsStubName;
                $content = $transformer->transform($jsContent, $name);

                if ($this->writeFile($filesystem, $jsTarget, $content, $force)) {
                    $createdFiles[] = 'resources/js/ui/'.$jsStubName;
                    warning("ACTION REQUIRED: Add 'import './ui/".str_replace('.js', '', $jsStubName)."';' to your resources/js/app.js");
                }
            }
        }

        // Inject CSS variables
        $cssInjected = false;
        if (! empty($remoteComponent['css_vars'])) {
            $cssPath = $projectPath.'/resources/css/app.css';
            if ($filesystem->exists($cssPath)) {
                $injector = new CssInjector;
                $isV4 = ($config['tailwind'] ?? 'v3') === 'v4';

                if ($isV4) {
                    spin(fn () => $injector->injectVars($cssPath, $remoteComponent['css_vars']), 'Injecting CSS variables...');
                    $cssInjected = true;
                }
            }
        }

        if (empty($createdFiles)) {
            error("Could not generate files for component: {$name}");

            return Command::FAILURE;
        }

        $this->showSummary($createdFiles, $cssInjected, $remoteComponent, $config);

        $source = $url ?? $repo ?? $registryUrl ?? 'remote';
        outro("✅ Component {$name} installed from {$source}!");

        return Command::SUCCESS;
    }

    /**
     * Display summary of created files.
     */
    protected function showSummary(array $createdFiles, bool $cssInjected, ?array $manifest, array $config): void
    {
        $summary = "Created files:\n";
        foreach ($createdFiles as $file) {
            $summary .= "- <comment>{$file}</comment>\n";
        }

        if ($cssInjected) {
            $summary .= "- <info>CSS variables injected into app.css</info>\n";
        } elseif ($manifest && ! empty($manifest['css_vars'])) {
            $isV4 = ($config['tailwind'] ?? 'v3') === 'v4';
            if (! $isV4) {
                note(
                    "Tailwind v3 detected. Please add these variables to your CSS manually:\n".
                        implode("\n", array_map(fn ($k, $v) => "$k: $v;", array_keys($manifest['css_vars']), $manifest['css_vars']))
                );
            }
        }

        note($summary);
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
