<?php

namespace App\Search\Client;

use App\Search\Interface\ConferenceSearchInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ApiConferenceSearch implements ConferenceSearchInterface
{
    public function __construct(
        #[Autowire(env: 'CONF_APIKEY')]
        private readonly string $apikey,
    ) {}

    public function searchByName(?string $name = null): array
    {
        // TODO: Implement searchByName() method.
    }
}
