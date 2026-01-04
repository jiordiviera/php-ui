<?php

declare(strict_types=1);

namespace Jiordiviera\PhpUi\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Jiordiviera\PhpUi\Exceptions\HttpException;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private GuzzleClient $client;

    private function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent' => 'PhpUi-Client/1.0',
        ];
    }

    public function buildHeaders(array $headers): self
    {
        $headers = array_merge($this->defaultHeaders(), $headers);
        $this->client = new GuzzleClient([
            'headers' => $headers,
        ]);

        return $this;
    }

    /**
     * @throws HttpException
     */
    public function get(string $uri, array $query = []): array
    {
        return $this->request('GET', $uri, [
            RequestOptions::QUERY => $query,
        ]);
    }

    /**
     * @throws HttpException
     */
    public function post(string $uri, array $data = []): array
    {
        return $this->request('POST', $uri, [
            RequestOptions::JSON => $data,
        ]);
    }

    /**
     * @throws HttpException
     */
    public function put(string $uri, array $data = []): array
    {
        return $this->request('PUT', $uri, [
            RequestOptions::JSON => $data,
        ]);
    }

    /**
     * @throws HttpException
     */
    public function patch(string $uri, array $data = []): array
    {
        return $this->request('PATCH', $uri, [
            RequestOptions::JSON => $data,
        ]);
    }

    /**
     * @throws HttpException
     */
    public function delete(string $uri): array
    {
        return $this->request('DELETE', $uri);
    }

    /**
     * @throws HttpException
     */
    private function request(
        string $method,
        string $uri,
        array $options = [],
    ): array {
        try {
            $response = $this->client->request($method, $uri, $options);

            return $this->parseResponse($response);
        } catch (GuzzleException $e) {
            throw new HttpException(
                $e->getMessage(),
                $e->getCode(),
                null,
                $e,
            );
        }
    }

    private function parseResponse(ResponseInterface $response): array
    {
        $body = (string) $response->getBody();

        if (empty($body)) {
            return [];
        }

        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpException(
                'Invalid JSON response: '.json_last_error_msg(),
            );
        }

        return $data;
    }
}
