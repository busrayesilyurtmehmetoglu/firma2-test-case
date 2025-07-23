<?php

namespace App\Libraries;

use CodeIgniter\HTTP\CURLRequest;

class RestService implements ExternalServiceInterface
{
    protected string $baseUrl;
    protected CURLRequest $client;

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->client = service('curlrequest');
    }

    public function fetch(string $endpoint, array $params = []): array
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');
        $response = $this->client->get($url, ['query' => $params]);
        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('REST API error: ' . $response->getStatusCode());
        }
        return json_decode($response->getBody(), true);
    }

    public function normalize(array $data): array
    {
        // Basit normalize örneği: olduğu gibi döndür
        return $data;
    }
} 