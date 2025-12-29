<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Core\Registry;

use Illuminate\Filesystem\Filesystem;

class RemoteRegistry
{
    protected Filesystem $files;

    protected string $defaultRegistry = 'https://raw.githubusercontent.com/jiordiviera/php-ui/main/registry.json';

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
     * Fetch a component from a registry.
     */
    public function fetchFromRegistry(string $component, ?string $registryUrl = null): ?array
    {
        $registryUrl = $registryUrl ?? $this->defaultRegistry;
        $registry = $this->getRegistry($registryUrl);

        if ($registry === null || ! isset($registry['components'][$component])) {
            return null;
        }

        $componentConfig = $registry['components'][$component];
        $baseUrl = $registry['baseUrl'] ?? dirname($registryUrl);

        $result = [
            'name' => $component,
            'description' => $componentConfig['description'] ?? '',
            'files' => [],
            'dependencies' => $componentConfig['dependencies'] ?? [],
            'css_vars' => $componentConfig['css_vars'] ?? [],
            'js_stubs' => [],
            'source' => $registryUrl,
        ];

        // Fetch blade files
        if (! empty($componentConfig['files'])) {
            foreach ($componentConfig['files'] as $stubName => $targetName) {
                $stubUrl = rtrim($baseUrl, '/').'/stubs/'.$stubName;
                $content = $this->httpGet($stubUrl);

                if ($content !== null) {
                    $result['files'][$stubName] = [
                        'content' => $content,
                        'target' => $targetName,
                    ];
                }
            }
        } else {
            // Default single file
            $stubUrl = rtrim($baseUrl, '/').'/stubs/'.$component.'.blade.php.stub';
            $content = $this->httpGet($stubUrl);

            if ($content !== null) {
                $result['files'][$component.'.blade.php.stub'] = [
                    'content' => $content,
                    'target' => strtolower($component).'.blade.php',
                ];
            }
        }

        // Fetch JS stubs
        if (! empty($componentConfig['js_stubs'])) {
            foreach ($componentConfig['js_stubs'] as $jsStubName) {
                $jsUrl = rtrim($baseUrl, '/').'/stubs/'.$jsStubName.'.stub';
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

        $registryUrl = "https://raw.githubusercontent.com/{$repo}/{$branch}/registry.json";

        return $this->fetchFromRegistry($component, $registryUrl);
    }

    /**
     * List components from a registry.
     */
    public function listFromRegistry(?string $registryUrl = null): array
    {
        $registryUrl = $registryUrl ?? $this->defaultRegistry;
        $registry = $this->getRegistry($registryUrl);

        if ($registry === null) {
            return [];
        }

        $components = [];

        foreach ($registry['components'] ?? [] as $name => $config) {
            $components[$name] = $config['description'] ?? $name;
        }

        return $components;
    }

    /**
     * Get the registry JSON.
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
