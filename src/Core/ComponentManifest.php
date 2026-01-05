<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Core;

use Illuminate\Support\Collection;
use Jiordiviera\PhpUi\Core\Registry\RemoteRegistry;

class ComponentManifest
{
    protected RemoteRegistry $registry;

    public function __construct(?RemoteRegistry $registry = null)
    {
        $this->registry = $registry ?? new RemoteRegistry;
    }

    /**
     * Get all available components.
     */
    public function all(): Collection
    {
        return collect($this->registry->listFromRegistry());
    }
}
