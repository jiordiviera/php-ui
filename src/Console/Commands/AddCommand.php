<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Jiordiviera\PhpUi\Core\Transformer\StubTransformer;
use Jiordiviera\PhpUi\Core\Transformer\DependencyManager;
use Jiordiviera\PhpUi\Core\Transformer\CssInjector;
use Jiordiviera\PhpUi\Core\ComponentManifest;
use Jiordiviera\PhpUi\Core\Detector\ProjectDetector;
use Illuminate\Filesystem\Filesystem;
use function Laravel\Prompts\info;
use function Laravel\Prompts\warning;
use function Laravel\Prompts\error;

class AddCommand extends Command
{
    protected static $defaultName = 'add';

    protected function configure()
    {
        $this->addArgument('component', InputArgument::REQUIRED, 'The name of the component');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $detector = new ProjectDetector();
        $projectPath = $detector->getProjectRoot();
        
        $configPath = $projectPath . '/php-ui.json';
        $name = $input->getArgument('component');
        
        if (!file_exists($configPath)) {
            error("No php-ui.json file found at {$projectPath}. Please run 'php-ui init' first.");
            return Command::FAILURE;
        }
        
        $filesystem = new Filesystem();
        $config = json_decode(file_get_contents($configPath), true);
        
        $manifest = ComponentManifest::get($name);
        $dependencyManager = new DependencyManager();
        
        // 1. Install Dependencies
        if ($manifest && !empty($manifest['dependencies'])) {
            if (!empty($manifest['dependencies']['composer'])) {
                $dependencyManager->checkAndInstall($manifest['dependencies']['composer'], 'composer');
            }
            if (!empty($manifest['dependencies']['npm'])) {
                $dependencyManager->checkAndInstall($manifest['dependencies']['npm'], 'npm');
            }
        }

        // 2. Transform and Create Files
        $transformer = new StubTransformer($config);

        if ($manifest && !empty($manifest['files'])) {
            foreach ($manifest['files'] as $stubName => $targetName) {
                $bladeStub = __DIR__ . "/../../../stubs/{$stubName}";
                $bladeTarget = $projectPath . "/" . $config['paths']['views'] . "/" . $targetName;

                if ($filesystem->exists($bladeStub)) {
                    $content = $transformer->transform($filesystem->get($bladeStub), $name);
                    $filesystem->ensureDirectoryExists(dirname($bladeTarget));
                    $filesystem->put($bladeTarget, $content);
                    info("Created View: " . $config['paths']['views'] . "/" . $targetName);
                } else {
                    warning("Stub not found: {$stubName}");
                }
            }
        } else {
            // Default behavior for single-file components
            $bladeStub = __DIR__ . "/../../../stubs/{$name}.blade.php.stub";
            $bladeTarget = $projectPath . "/" . $config['paths']['views'] . "/" . strtolower($name) . ".blade.php";

            if ($filesystem->exists($bladeStub)) {
                $content = $transformer->transform($filesystem->get($bladeStub), $name);
                $filesystem->ensureDirectoryExists(dirname($bladeTarget));
                $filesystem->put($bladeTarget, $content);
                info("Created View: " . $config['paths']['views'] . "/" . strtolower($name) . ".blade.php");
            } else {
                 error("Stub not found for component: {$name}");
                 return Command::FAILURE;
            }
        }


        // Optional: PHP Class (only if stub exists, e.g. for complex components)
        $phpStub = __DIR__ . "/../../../stubs/{$name}.php.stub";
        if ($filesystem->exists($phpStub)) {
             $phpTarget = $projectPath . "/" . $config['paths']['components'] . "/" . ucfirst($name) . ".php";
             $content = $transformer->transform($filesystem->get($phpStub), $name);
             $filesystem->ensureDirectoryExists(dirname($phpTarget));
             $filesystem->put($phpTarget, $content);
             info("Created Class: " . $config['paths']['components'] . "/" . ucfirst($name) . ".php");
        }


        // 2b. Handle JS Stubs
        if ($manifest && !empty($manifest['js_stubs'])) {
            $jsDir = $projectPath . '/resources/js/ui';
            $filesystem->ensureDirectoryExists($jsDir);

            foreach ($manifest['js_stubs'] as $jsStubName) {
                $jsStubPath = __DIR__ . "/../../../stubs/{$jsStubName}.stub";
                $jsTarget = $jsDir . '/' . $jsStubName;

                if ($filesystem->exists($jsStubPath)) {
                    $content = $transformer->transform($filesystem->get($jsStubPath), $name);
                    $filesystem->put($jsTarget, $content);
                    
                    $relativeJsPath = "./ui/" . str_replace('.js', '', $jsStubName);
                    info("Created JS file: resources/js/ui/{$jsStubName}");
                    warning("Please add 'import '{$relativeJsPath}';' to your resources/js/app.js");
                }
            }
        }

        // 3. Inject CSS Variables
        if ($manifest && !empty($manifest['css_vars'])) {
            $cssPath = $projectPath . '/resources/css/app.css';
            
            if ($filesystem->exists($cssPath)) {
                $injector = new CssInjector();
                // Determine mode based on config version (simple heuristic for now)
                $isV4 = ($config['tailwind'] ?? 'v3') === 'v4';
                
                // For now CssInjector is hardcoded for @theme (v4 style), 
                // but we can wrap it or update it later.
                // If it's v4, we use the injector. 
                // If v3, we might need to append to :root. 
                // Let's rely on CssInjector but I will need to update CssInjector to handle v3/v4 better 
                // or just accept that this tool pushes towards v4 or compatible v3 setup.
                // The current CssInjector adds @theme block which breaks v3.
                
                if ($isV4) {
                     $injector->injectVars($cssPath, $manifest['css_vars']);
                     info("Injected CSS variables into app.css");
                } else {
                    // Manual warning or TODO for v3
                    // For v3, we can just append to file if we want to support it simply
                     warning("Tailwind v3 detected. Please manually add these variables to your CSS or tailwind.config.js:");
                     foreach ($manifest['css_vars'] as $key => $val) {
                         $output->writeln("<comment>$key: $val;</comment>");
                     }
                }
            } else {
                warning("Could not find resources/css/app.css. Skipped CSS injection.");
            }
        }

        info("Component {$name} added successfully!");
        return Command::SUCCESS;
    }
}