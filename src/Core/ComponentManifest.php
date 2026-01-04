<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Core;

use Illuminate\Support\Collection;
use Jiordiviera\PhpUi\Core\Registry\RemoteRegistry;

class ComponentManifest
{
    protected RemoteRegistry $remoteRegistry;
    protected static ?array $registry = null;

    public function __construct(?RemoteRegistry $registry = null)
    {
        $this->remoteRegistry = $registry ?? new RemoteRegistry();
    }

    /**
     * Get the configuration for a specific component.
     */
    public function get(string $component): ?string
    {
        return $this->all()[$component] ?? null;
    }

    /**
     * Get all available components.
     */
    public function all(): Collection
    {
        return collect($this->remoteRegistry->listFromRegistry());
    }

    /**
     * Load the registry from the JSON file.
     */
    protected static function loadRegistry(): array
    {
        if (self::$registry !== null) {
            return self::$registry;
        }

        $registryPath = __DIR__ . '/../../registry.json';

        if (! file_exists($registryPath)) {
            return ['components' => []];
        }

        $content = file_get_contents($registryPath);
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['components' => []];
        }

        self::$registry = $data;

        return self::$registry;
    }

    /**
     * Get registry metadata (name, version, baseUrl).
     */
    public static function getRegistryInfo(): array
    {
        $registry = self::loadRegistry();

        return [
            'name' => $registry['name'] ?? 'PHP-UI',
            'version' => $registry['version'] ?? '1.0.0',
            'baseUrl' => $registry['baseUrl'] ?? '',
        ];
    }

    /**
     * Clear cached registry (useful for testing).
     */
    public static function clearCache(): void
    {
        self::$registry = null;
    }
}
