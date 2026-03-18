<?php

namespace App\Search\Client;

use App\Search\Interface\ConferenceSearchInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiConferenceSearch implements ConferenceSearchInterface
{
    public function __construct(
        private readonly HttpClientInterface $client,
        #[Autowire(env: 'CONF_APIKEY')]
        private readonly string $apikey,
    ) {}

    public function searchByName(?string $name = null): array
    {
        return $this->client->request('GET', 'https://www.devevents-api.fr/events', [
            'query' => ['name' => $name],
            'headers' => [
                'apikey' => $this->apikey,
                'Accept' => 'application/json',
            ]
        ])->toArray();
    }
}
