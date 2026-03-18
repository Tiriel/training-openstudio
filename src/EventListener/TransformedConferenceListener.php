<?php

namespace App\EventListener;

use App\Entity\Conference;
use App\Entity\Organization;
use App\Event\TransformedConferenceEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
final class TransformedConferenceListener
{
    public function __construct(
        private readonly EntityManagerInterface $manager
    ) {}

    public function __invoke(TransformedConferenceEvent $event): void
    {
        $dto = $event->getDto();
        $conference = $this->manager->getRepository(Conference::class)
            ->findOneBy([
                'name' => $dto->getName(),
                'startAt' => $dto->getStartAt(),
            ]);

        if (null === $conference) {
            $conference = $dto->toEntity();
            $conference->getOrganizations()->map(function (Organization $organization) {
                return $this->manager->getRepository(Organization::class)
                    ->findOneBy([
                        'name' => $organization->getName(),
                        'createdAt' => $organization->getCreatedAt(),
                    ]) ?? $organization;
            });

            $this->manager->persist($conference);
            $this->manager->flush();
        }

        $event->setConference($conference);
    }
}
