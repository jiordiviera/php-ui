<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Console;

use function Laravel\Prompts\note;

class Logo
{
    public static function render(): void
    {
        $ascii = <<<'ASCII'
  ____  _   _ ____        _   _ ___
 |  _ \| | | |  _ \      | | | |_ _|
 | |_) | |_| | |_) |_____| | | || |
 |  __/|  _  |  __/|_____| |_| || |
 |_|   |_| |_|_|          \___/|___|

ASCII;

        note($ascii."\n   Laravel UI Component Scaffolder");
    }
}
