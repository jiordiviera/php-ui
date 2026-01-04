<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Core\Registry;

use Illuminate\Filesystem\Filesystem;

class RemoteRegistry
{
    protected Filesystem $files;

    protected string $defaultRegistry = 'https://raw.githubusercontent.com/jiordiviera/php-ui/main';

    protected string $stubsBaseUrl = 'https://raw.githubusercontent.com/jiordiviera/php-ui/main/stubs';

    public function __construct(?Filesystem $files = null)
    {
        $this->files = $files ?? new Filesystem;
    }

    /**
     * Fetch a component from a direct URL.
     */
    public function fetchFromUrl(string $url): ?array
    {
        $content = $this->httpGet($url);

        if ($content === null) {
            return null;
        }

        // Determine file type from URL
        $filename = basename(parse_url($url, PHP_URL_PATH) ?? 'component.blade.php.stub');

        return [
            'name' => $this->extractComponentName($filename),
            'files' => [
                'blade' => $content,
            ],
            'source' => $url,
        ];
    }

    /**
     * Fetch a component from registry (GitHub-based).
     */
    public function fetchFromRegistry(string $component, ?string $registryUrl = null): ?array
    {
        $registryUrl = $registryUrl ?? $this->defaultRegistry;

        // Handle file:// protocol for local registries
        if (str_starts_with($registryUrl, 'file://')) {
            $localPath = substr($registryUrl, 7);
            
            if (is_dir($localPath)) {
                // Directory: look for registry.json
                $registryIndexPath = rtrim($localPath, '/').'/registry.json';
                if (file_exists($registryIndexPath)) {
                    $registryIndex = json_decode(file_get_contents($registryIndexPath), true);
                    if (isset($registryIndex['components'][$component])) {
                        $componentData = $registryIndex['components'][$component];
                        return $this->processLocalComponent($component, $componentData, $localPath);
                    }
                }
            } elseif (file_exists($localPath)) {
                // Direct file
                $registryIndex = json_decode(file_get_contents($localPath), true);
                if (isset($registryIndex['components'][$component])) {
                    $componentData = $registryIndex['components'][$component];
                    $baseDir = dirname($localPath);
                    return $this->processLocalComponent($component, $componentData, $baseDir);
                }
            }
            
            return null;
        }

        // Try individual component JSON first for remote registries
        $componentJsonUrl = rtrim($registryUrl, '/')."/registry/{$component}.json";
        $componentData = $this->getComponentJson($componentJsonUrl);

        if ($componentData !== null) {
            return $this->processIndividualComponent($component, $componentData, $registryUrl);
        }

        return null;
    }

    /**
     * Process individual component JSON.
     */
    protected function processIndividualComponent(string $component, array $componentData, string $baseUrl): array
    {
        $result = [
            'name' => $component,
            'description' => $componentData['description'] ?? '',
            'files' => [],
            'dependencies' => $componentData['dependencies'] ?? [],
            'css_vars' => $componentData['css_vars'] ?? [],
            'js_stubs' => [],
            'source' => rtrim($baseUrl, '/')."/registry/{$component}.json",
            'type' => $componentData['type'] ?? 'registry:ui',
            'registryDependencies' => $componentData['registryDependencies'] ?? [],
        ];

        // Process files - object format (PHP-UI style with stub references)
        if (! empty($componentData['files'])) {
            foreach ($componentData['files'] as $stubName => $targetName) {
                $stubUrl = $this->stubsBaseUrl.'/'.$stubName;
                $content = $this->httpGet($stubUrl);

                if ($content !== null) {
                    $result['files'][$stubName] = [
                        'content' => $content,
                        'target' => $targetName,
                    ];
                }
            }
        }

        // Fetch JS stubs
        if (! empty($componentData['js_stubs'])) {
            foreach ($componentData['js_stubs'] as $jsStubName) {
                $jsUrl = $this->stubsBaseUrl.'/'.$jsStubName.'.stub';
                $content = $this->httpGet($jsUrl);

                if ($content !== null) {
                    $result['js_stubs'][$jsStubName] = $content;
                }
            }
        }

        return $result;
    }

    /**
     * Fetch from a GitHub repository.
     * Format: owner/repo or owner/repo@branch
     */
    public function fetchFromGitHub(string $component, string $repo, ?string $branch = null): ?array
    {
        $branch = $branch ?? 'main';

        // Parse repo format: owner/repo or owner/repo@branch
        if (str_contains($repo, '@')) {
            [$repo, $branch] = explode('@', $repo, 2);
        }

        $registryUrl = "https://raw.githubusercontent.com/{$repo}/{$branch}";

        return $this->fetchFromRegistry($component, $registryUrl);
    }

    /**
     * List components from a registry.
     */
    public function listFromRegistry(?string $registryUrl = null): array
    {
        $registryUrl = $registryUrl ?? $this->defaultRegistry;
        $components = [];

        // If custom URL is provided, try it directly
        if ($registryUrl !== $this->defaultRegistry) {
            // Handle file:// protocol and http(s) URLs
            if (str_starts_with($registryUrl, 'file://')) {
                // Remove file:// prefix and treat as local path
                $localPath = substr($registryUrl, 7);
                if (is_dir($localPath)) {
                    // If it's a directory, look for registry.json inside
                    $registryIndexPath = rtrim($localPath, '/').'/registry.json';
                    if (file_exists($registryIndexPath)) {
                        $registryIndex = json_decode(file_get_contents($registryIndexPath), true);
                    }
                } elseif (file_exists($localPath)) {
                    // If it's a file, use it directly
                    $registryIndex = json_decode(file_get_contents($localPath), true);
                }
            } else {
                // Try to get individual component files first
                $registryIndexUrl = rtrim($registryUrl, '/').'/registry.json';
                $registryIndex = $this->getRegistry($registryIndexUrl);
            }

            if ($registryIndex !== null && isset($registryIndex['components'])) {
                // New format with registry index
                foreach ($registryIndex['components'] as $name => $config) {
                    $components[$name] = $config['description'] ?? $name;
                }
            } else {
                // Try legacy format
                if (str_starts_with($registryUrl, 'file://')) {
                    $localPath = substr($registryUrl, 7);
                    if (file_exists($localPath)) {
                        $registry = json_decode(file_get_contents($localPath), true);
                    }
                } else {
                    $registry = $this->getRegistry($registryUrl);
                }
                if ($registry !== null) {
                    foreach ($registry['components'] ?? [] as $name => $config) {
                        $components[$name] = $config['description'] ?? $name;
                    }
                }
            }
        } else {
            // Default registry: try individual files first
            $registryIndexUrl = rtrim($registryUrl, '/').'/registry.json';
            $registryIndex = $this->getRegistry($registryIndexUrl);

            if ($registryIndex !== null && isset($registryIndex['components'])) {
                // New format with registry index
                foreach ($registryIndex['components'] as $name => $config) {
                    $components[$name] = $config['description'] ?? $name;
                }
            } else {
                // Fallback: try to read individual component files from legacy registry.json
                $legacyRegistryUrl = 'https://raw.githubusercontent.com/jiordiviera/php-ui/main/registry.json';
                $registry = $this->getRegistry($legacyRegistryUrl);

                if ($registry !== null) {
                    foreach ($registry['components'] ?? [] as $name => $config) {
                        $components[$name] = $config['description'] ?? $name;
                    }
                }
            }
        }

        return $components;
    }

    /**
     * Get component JSON from individual component file.
     */
    protected function getComponentJson(string $url): ?array
    {
        $content = $this->httpGet($url);

        if ($content === null) {
            return null;
        }

        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return $data;
    }

    /**
     * Get registry JSON.
     */
    protected function getRegistry(string $url): ?array
    {
        $content = $this->httpGet($url);

        if ($content === null) {
            return null;
        }

        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return $data;
    }

    /**
     * Perform HTTP GET request.
     */
    protected function httpGet(string $url): ?string
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: PHP-UI CLI/1.0',
                    'Accept: */*',
                ],
                'timeout' => 30,
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);

        $content = @file_get_contents($url, false, $context);

        if ($content === false) {
            return null;
        }

        return $content;
    }

    /**
     * Extract component name from filename.
     */
    protected function extractComponentName(string $filename): string
    {
        // Remove extensions like .blade.php.stub or .php.stub
        $name = preg_replace('/\.(blade\.php|php|js)\.stub$/', '', $filename);

        return $name ?? 'component';
    }

    /**
     * Set custom default registry URL.
     */
    public function setDefaultRegistry(string $url): self
    {
        $this->defaultRegistry = $url;

        return $this;
    }
}
