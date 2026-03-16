<?php

namespace App\Entity;

use App\Repository\ConferenceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ConferenceRepository::class)]
class Conference
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $accessible = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $prerequisites = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $endAt = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isAccessible(): ?bool
    {
        return $this->accessible;
    }

    public function setAccessible(bool $accessible): static
    {
        $this->accessible = $accessible;

        return $this;
    }

    public function getPrerequisites(): ?string
    {
        return $this->prerequisites;
    }

    public function setPrerequisites(?string $prerequisites): static
    {
        $this->prerequisites = $prerequisites;

        return $this;
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
}
