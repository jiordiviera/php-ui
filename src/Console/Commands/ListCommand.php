<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Jiordiviera\PhpUi\Core\ComponentManifest;
use function Laravel\Prompts\intro;

class ListCommand extends Command
{
    protected static $defaultName = 'list-components';

    protected function configure()
    {
        $this->setDescription('List all available components');
        $this->setAliases(['list']); 
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        intro('📦 Available UI Components');

        $components = ComponentManifest::all();
        $rows = [];

        foreach ($components as $name => $config) {
            $rows[] = [
                "<info>{$name}</info>",
                $config['description'] ?? 'No description available',
            ];
        }

        $table = new Table($output);
        $table->setHeaders(['Component', 'Description'])
              ->setRows($rows);
        
        $table->render();

        return Command::SUCCESS;
    }
}
