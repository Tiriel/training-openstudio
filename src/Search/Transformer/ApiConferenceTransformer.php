<?php

namespace App\Search\Transformer;

use App\Dto\ApiConference;
use App\Dto\ApiOrganization;
use Symfony\Component\Uid\Uuid;

class ApiConferenceTransformer
{
    public function transform(array $data): ApiConference
    {
        $dto = (new ApiConference())
            ->setId(Uuid::fromString($data['id']) ?? null)
            ->setName($data['name'] ?? null)
            ->setDescription($data['description'] ?? null)
            ->setAccessible($data['accessible'] ?? null)
            ->setPrerequisites($data['prerequisites'] ?? null)
            ->setStartAt(new \DateTimeImmutable($data['startDate'] ?? null))
            ->setEndAt(new \DateTimeImmutable($data['endDate'] ?? null))
        ;

        foreach ($data['organizations'] as $organization) {
            $dto->addOrganization(
                (new ApiOrganization())
                    ->setId(Uuid::fromString($organization['id']) ?? null)
                    ->setName($organization['name'] ?? null)
                    ->setPresentation($organization['presentation'] ?? null)
                    ->setCreatedAt(new \DateTimeImmutable($organization['createdAt'] ?? null))
            );
        }

        return $dto;
    }
}
