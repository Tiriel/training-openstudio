<?php

namespace App\Search\Handler;

use App\Constants\Roles;
use App\Dto\ApiConference;
use App\Entity\Conference;
use App\Entity\Organization;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class TransformedConferenceHandler
{
    public function __construct(
        private readonly EntityManagerInterface $manager,
        private readonly AuthorizationCheckerInterface $checker,
    ) {}

    public function handle(ApiConference $dto): ?Conference
    {
        if (!$this->checker->isGranted(Roles::WEBSITE)
            && !$this->checker->isGranted(Roles::ORGANIZER)
        ) {
            return null;
        }

        $conference = $this->manager->getRepository(Conference::class)
            ->findOneBy([
                'name' => $dto->getName(),
                'startAt' => $dto->getStartAt(),
            ]);

        if (null === $conference) {
            $conference = $dto->toEntity();
            $conference->getOrganizations()->map(function (Organization $organization) {
                return $this->getOrganization($organization);
            });

            $this->manager->persist($conference);
            $this->manager->flush();
        }

        return $conference;
    }

    private function getOrganization(Organization $organization): Organization
    {
        return $this->manager->getRepository(Organization::class)
                ->findOneBy([
                    'name' => $organization->getName(),
                    'createdAt' => $organization->getCreatedAt(),
                ]) ?? $organization;
    }
}
