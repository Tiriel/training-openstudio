<?php

namespace App\Search\Decorator;

use App\Search\Client\ApiConferenceSearch;
use App\Search\Interface\ConferenceSearchInterface;
use App\Search\Transformer\ApiConferenceTransformer;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator(ApiConferenceSearch::class, priority: 10)]
class TransformerApiConferenceSearch implements ConferenceSearchInterface
{
    public function __construct(
        private readonly ConferenceSearchInterface $inner,
        private readonly ApiConferenceTransformer $transformer,
    ) {}

    public function searchByName(?string $name = null): array
    {
        $conferences = $this->inner->searchByName($name);

        return \array_map(
            fn(array $conf) => $this->transformer->transform($conf),
            $conferences
        );
    }
}
