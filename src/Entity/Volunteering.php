<?php

namespace App\Entity;

use App\Repository\VolunteeringRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VolunteeringRepository::class)]
class Volunteering
{
    #[Groups(['conference:list', 'conference:get'])]
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[Groups(['conference:list', 'conference:get'])]
    #[Assert\GreaterThanOrEqual(propertyPath: 'conference.startAt')]
    #[ORM\Column]
    private ?\DateTimeImmutable $startAt = null;

    #[Groups(['conference:list', 'conference:get'])]
    #[Assert\GreaterThanOrEqual(propertyPath: 'startAt')]
    #[Assert\LessThanOrEqual(propertyPath: 'conference.endAt')]
    #[ORM\Column]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\ManyToOne(inversedBy: 'volunteerings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Conference $conference = null;

    #[Groups(['conference:list', 'conference:get'])]
    #[ORM\ManyToOne(inversedBy: 'volunteerings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $forUser = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getConference(): ?Conference
    {
        return $this->conference;
    }

    public function setConference(?Conference $conference): static
    {
        $this->conference = $conference;

        return $this;
    }

    public function getForUser(): ?User
    {
        return $this->forUser;
    }

    public function setForUser(?User $forUser): static
    {
        $this->forUser = $forUser;

        return $this;
    }
}
