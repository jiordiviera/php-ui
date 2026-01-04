<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Console\Commands;

use Illuminate\Filesystem\Filesystem;
use Jiordiviera\PhpUi\Console\Logo;
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
        $this->setDescription('Add a UI component to your project');
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
        $repo = $input->getOption('repo');

        $detector = new ProjectDetector;
        $projectPath = $detector->getProjectRoot();
        $configPath = $projectPath.'/php-ui.json';

        if (! file_exists($configPath)) {
            error("⚠️  No php-ui.json found. Run 'php-ui init' first.");

            return Command::FAILURE;
        }

        $filesystem = new Filesystem;
        $config = json_decode(file_get_contents($configPath), true);

        // If no component name provided, search from remote registry
        if (! $name) {
            $registry = new RemoteRegistry;
            $components = spin(
                fn () => $registry->listFromRegistry(),
                '📦 Loading component registry...'
            );

            if (empty($components)) {
                error('❌ No components found in registry.');

                return Command::FAILURE;
            }

            info('Found '.count($components).' components available');

            $name = search(
                label: 'Search for a component',
                options: fn (string $value) => collect($components)
                    ->filter(fn ($desc, $key) => strlen($value) === 0 || str_contains($key, $value))
                    ->mapWithKeys(fn ($desc, $key) => [$key => sprintf('%-18s │ %s', $key, $desc)])
                    ->toArray(),
                placeholder: 'Type to filter components...',
                hint: 'Use arrow keys to navigate, Enter to select'
            );

            if (! $name) {
                error('❌ No component selected.');

                return Command::FAILURE;
            }
        }

        return $this->installFromRemote($name, $input, $output, $config, $projectPath, $filesystem, $force);
    }

    /**
     * Install component from remote source (URL, registry, or GitHub).
     */
    protected function installFromRemote(
        string $name,
        InputInterface $input,
        OutputInterface $output,
        array $config,
        string $projectPath,
        Filesystem $filesystem,
        bool $force
    ): int {
        $url = $input->getOption('url');
        $repo = $input->getOption('repo');

        $registry = new RemoteRegistry;
        $remoteComponent = null;

        // Fetch from direct URL
        if ($url) {
            $remoteComponent = spin(
                fn () => $registry->fetchFromUrl($url),
                '📥 Downloading from URL...'
            );

            if (! $remoteComponent) {
                error("❌ Failed to fetch component from: {$url}");

                return Command::FAILURE;
            }

            $name = $remoteComponent['name'];
        }

        // Fetch from GitHub repository
        if ($repo) {
            $remoteComponent = spin(
                fn () => $registry->fetchFromGitHub($name, $repo),
                "📥 Downloading {$name} from GitHub..."
            );

            if (! $remoteComponent) {
                error("❌ Failed to fetch '{$name}' from: {$repo}");

                return Command::FAILURE;
            }
        }

        // Fetch from registry
        if (! $url && ! $repo) {
            $remoteComponent = spin(
                fn () => $registry->fetchFromRegistry($name),
                "📥 Downloading {$name}..."
            );

            if (! $remoteComponent) {
                error("❌ Component '{$name}' not found in registry.");

                return Command::FAILURE;
            }
        }

        if (! $remoteComponent) {
            error('❌ No component to install.');

            return Command::FAILURE;
        }

        // Show component info
        $this->showComponentInfo($name, $remoteComponent);

        // Install dependencies
        $dependencyManager = new DependencyManager;
        $depsInstalled = ['composer' => [], 'npm' => []];

        if (! empty($remoteComponent['dependencies'])) {
            if (! empty($remoteComponent['dependencies']['composer'])) {
                info('📦 Installing Composer dependencies...');
                $depsInstalled['composer'] = $remoteComponent['dependencies']['composer'];
                $dependencyManager->checkAndInstall($remoteComponent['dependencies']['composer'], 'composer', $force);
            }
            if (! empty($remoteComponent['dependencies']['npm'])) {
                info('📦 Installing NPM dependencies...');
                $depsInstalled['npm'] = $remoteComponent['dependencies']['npm'];
                $dependencyManager->checkAndInstall($remoteComponent['dependencies']['npm'], 'npm', $force);
            }
        }

        // Transform and write files
        $transformer = new StubTransformer($config);
        $createdFiles = [];

        info('📝 Creating component files...');

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
        $jsFiles = [];
        if (! empty($remoteComponent['js_stubs'])) {
            $jsDir = $projectPath.'/resources/js/ui';
            $filesystem->ensureDirectoryExists($jsDir);

            foreach ($remoteComponent['js_stubs'] as $jsStubName => $jsContent) {
                $jsTarget = $jsDir.'/'.$jsStubName;
                $content = $transformer->transform($jsContent, $name);

                if ($this->writeFile($filesystem, $jsTarget, $content, $force)) {
                    $createdFiles[] = 'resources/js/ui/'.$jsStubName;
                    $jsFiles[] = str_replace('.js', '', (string) $jsStubName);
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
                    spin(fn () => $injector->injectVars($cssPath, $remoteComponent['css_vars']), '🎨 Injecting CSS variables...');
                    $cssInjected = true;
                }
            }
        }

        if (empty($createdFiles)) {
            error("❌ Could not generate files for: {$name}");

            return Command::FAILURE;
        }

        // Show summary
        $this->showInstallSummary($name, $createdFiles, $jsFiles, $cssInjected, $depsInstalled, $remoteComponent, $config);

        return Command::SUCCESS;
    }

    /**
     * Show component information before installation.
     */
    protected function showComponentInfo(string $name, array $component): void
    {
        note("📦 Component: {$name}");

        if (! empty($component['description'])) {
            info('   '.$component['description']);
        }
    }

    /**
     * Display installation summary.
     */
    protected function showInstallSummary(
        string $name,
        array $createdFiles,
        array $jsFiles,
        bool $cssInjected,
        array $depsInstalled,
        array $manifest,
        array $config
    ): void {
        echo "\n";
        info('┌─────────────────────────────────────────────────────────────┐');
        info("│  ✅ Component <comment>{$name}</comment> installed successfully!");
        info('└─────────────────────────────────────────────────────────────┘');
        echo "\n";

        // Files created
        note('📁 Files created:');
        foreach ($createdFiles as $file) {
            info("   └─ <comment>{$file}</comment>");
        }

        // Dependencies
        if (! empty($depsInstalled['composer']) || ! empty($depsInstalled['npm'])) {
            echo "\n";
            note('📦 Dependencies installed:');
            foreach ($depsInstalled['composer'] as $dep) {
                info("   └─ <info>composer</info>: {$dep}");
            }
            foreach ($depsInstalled['npm'] as $dep) {
                info("   └─ <info>npm</info>: {$dep}");
            }
        }

        // CSS variables
        if ($cssInjected) {
            echo "\n";
            info('   🎨 CSS variables injected into app.css');
        } elseif (! empty($manifest['css_vars'])) {
            $isV4 = ($config['tailwind'] ?? 'v3') === 'v4';
            if (! $isV4) {
                echo "\n";
                warning('⚠️  Tailwind v3 detected. Add these CSS variables manually:');
                foreach ($manifest['css_vars'] as $k => $v) {
                    info("   {$k}: {$v};");
                }
            }
        }

        // JS imports required
        if (! empty($jsFiles)) {
            echo "\n";
            warning('⚠️  Action required - Add to your resources/js/app.js:');
            foreach ($jsFiles as $jsFile) {
                info("   <comment>import './ui/{$jsFile}';</comment>");
            }
        }

        // Usage hint
        echo "\n";
        note('💡 Usage:');
        info("   <comment><x-ui.{$name} /></comment>");
        echo "\n";

        outro('🎉 Happy coding!');
    }

    protected function writeFile(Filesystem $filesystem, string $path, string $content, bool $force): bool
    {
        if ($filesystem->exists($path) && ! $force) {
            if (! confirm("File already exists: {$path}. Overwrite?", default: false)) {
                return false;
            }
        }

        $filesystem->ensureDirectoryExists(dirname($path));
        $filesystem->put($path, $content);

        return true;
    }
}
