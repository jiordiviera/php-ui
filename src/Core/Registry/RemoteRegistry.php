<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Core\Registry;

use Illuminate\Filesystem\Filesystem;
use Jiordiviera\PhpUi\Http\Client;

use function Laravel\Prompts\info;

class RemoteRegistry
{

    protected ?Filesystem $files;
    protected Client $httpClient;
    protected string $defaultRegistry = 'https://raw.githubusercontent.com/jiordiviera/php-ui/main';

    protected string $stubsBaseUrl = 'https://raw.githubusercontent.com/jiordiviera/php-ui/main/stubs';

    protected string $registryBaseUrl = 'https://raw.githubusercontent.com/jiordiviera/php-ui/main';

    public function __construct(?Filesystem $files = null, ?Client $httpClient = null)
    {
        $this->files = $files ?? new Filesystem();
        $this->httpClient = $httpClient ?? new Client();
    }

    /**
     * Fetch a component from a direct URL.
     *
     * @param  string  $url  The direct URL to the Blade component file
     * @return array{
     *     name: string,
     *     files: array{blade: string},
     *     source: string
     * }|null
     */
    public function fetchFromUrl(string $url): ?array
    {
        $content = $this->httpGet($url);

        if ($content === null) {
            return null;
        }

        // Determine file type from URL
        $filename = basename(parse_url($url, PHP_URL_PATH));

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
        $registryUrl = $registryUrl ?? $this->registryBaseUrl;

        // Always try direct component file first for complete data
        $componentUrl = $registryUrl . "/registry/{$component}.json";
        $componentData = $this->getComponentJson($componentUrl);

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
            'source' => rtrim($baseUrl, '/') . "/registry/{$component}.json",
            'type' => $componentData['type'] ?? 'registry:ui',
            'registryDependencies' => $componentData['registryDependencies'] ?? [],
        ];

        // Process files - object format (PHP-UI style with stub references)
        if (! empty($componentData['files'])) {
            foreach ($componentData['files'] as $stubName => $targetName) {
                $stubUrl = $this->stubsBaseUrl . '/' . $stubName;
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
                $jsUrl = $this->stubsBaseUrl . '/' . $jsStubName . '.stub';
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
     *
     * @return array<string, string> Component name => description
     */
    public function listFromRegistry(?string $registryUrl = null): array
    {
        $registryUrl = $registryUrl ?? $this->registryBaseUrl . '/registry.json';

        $registryData = $this->getRegistry($registryUrl);

        if ($registryData === null || empty($registryData['components'])) {
            return [];
        }

        return $registryData['components'];
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
