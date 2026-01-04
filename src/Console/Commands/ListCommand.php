<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Console\Commands;

use Jiordiviera\PhpUi\Console\Logo;
use Jiordiviera\PhpUi\Core\ComponentManifest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        Logo::render();

        $manifest = new ComponentManifest();
        $components = $manifest->all();
        $rows = [];

        foreach ($components as $name => $description) {
            $rows[] = [
                "<info>{$name}</info>",
                $description ?? 'No description available',
            ];
        }

        $table = new Table($output);
        $table->setHeaders(['Component', 'Description'])
            ->setRows($rows);

        $table->render();

        return Command::SUCCESS;
    }
}
