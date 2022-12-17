<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Client
{
    public function __construct(
        private readonly HttpClientInterface $pokeApiClient,
    ) {
    }

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        return $this->pokeApiClient->request($method, $url, $options);
    }

    public function save(string $entrypoint, array $data, array $options = []): array
    {
        $options = array_merge($options, ['json' => $data]);

        if (isset($data['@id'])) {
            return $this->decodeResponse($this->pokeApiClient->request(Request::METHOD_PUT, $data['@id'], $options));
        }

        return $this->decodeResponse($this->pokeApiClient->request(Request::METHOD_POST, $entrypoint, $options));
    }

    public function findOneBy(string $url, array $options = []): array
    {
        $results = $this->decodeResponse($this->pokeApiClient->request(Request::METHOD_GET, $url, ['query' => $options]));

        if (1 !== \count($results['hydra:member'])) {
            throw new \RangeException(sprintf('Found %s when 1 was expected.', \count($results['hydra:member'])));
        }

        return $results['hydra:member'][0];
    }

    public function findBy(string $url, array $query = [], $orders = []): array
    {
        if ($orders) {
            if (array_keys($orders) === range(0, \count($orders) - 1)) {
                $orders = array_fill_keys($orders, '');
            }

            foreach ($orders as $field => $direction) {
                $query[sprintf('order[%s]', $field)] = $direction;
            }
        }
        $options['query'] = $query;

        $results = $this->decodeResponse($this->pokeApiClient->request(Request::METHOD_GET, $url, $options));

        return $results['hydra:member'];
    }

    public function find(string $resource, $id = null, array $options = [])
    {
        $entryPoint = null === $id ? $resource : sprintf('%s/%s', $resource, $id);

        return $this->decodeResponse($this->pokeApiClient->request(Request::METHOD_GET, $entryPoint, $options));
    }

    public function get($entryPoint, array $options = [])
    {
        return $this->decodeResponse($this->pokeApiClient->request(Request::METHOD_GET, $entryPoint, $options));
    }

    private function decodeResponse(ResponseInterface $response): array
    {
        return json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR);
    }
}
