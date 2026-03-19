<?php

namespace App\Search\Handler;

use App\Search\Database\DatabaseConferenceSearch;
use App\Search\Interface\ConferenceSearchInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

#[AsAlias]
class ConferenceSearchHandler implements ConferenceSearchInterface
{
    public function __construct(
        /** @var ConferenceSearchInterface[] $searches */
        #[AutowireIterator('conferences.search')]
        private readonly iterable $searches,
    ) {}

    public function searchByName(?string $name = null): array
    {
        if (null === $name) {
            foreach ($this->searches as $search) {
                if ($search instanceof DatabaseConferenceSearch) {
                    return $search->searchByName($name);
                }
            }
        }
        
        $conferences = [];

        foreach ($this->searches as $search) {
            $conferences = \array_merge($conferences, $search->searchByName($name));
        }

        return \array_unique($conferences, SORT_REGULAR);
    }
}
