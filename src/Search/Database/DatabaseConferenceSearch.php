<?php

namespace App\Search\Database;

use App\Repository\ConferenceRepository;

class DatabaseConferenceSearch
{
    public function __construct(
        private readonly ConferenceRepository $repository,
    ) {}

    public function searchByName(?string $name): array
    {
        if (null === $name) {
            return $this->repository->findAll();
        }

        return $this->repository->findLikeName($name);
    }
}
