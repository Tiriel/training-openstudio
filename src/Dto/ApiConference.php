<?php

namespace App\Dto;

use Symfony\Component\Uid\Uuid;

class ApiConference
{
    private ?Uuid $id = null;
    private ?string $name = null;
    private ?string $description = null;
    private ?bool $accessible = null;
    private ?string $prerequisites = null;
    private ?\DateTimeImmutable $startAt = null;
    private ?\DateTimeImmutable $endAt = null;
    private array $volunteerings = [];
    private array $organizations = [];

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(?Uuid $id): ApiConference
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ApiConference
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): ApiConference
    {
        $this->description = $description;
        return $this;
    }

    public function getAccessible(): ?bool
    {
        return $this->accessible;
    }

    public function setAccessible(?bool $accessible): ApiConference
    {
        $this->accessible = $accessible;
        return $this;
    }

    public function getPrerequisites(): ?string
    {
        return $this->prerequisites;
    }

    public function setPrerequisites(?string $prerequisites): ApiConference
    {
        $this->prerequisites = $prerequisites;
        return $this;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTimeImmutable $startAt): ApiConference
    {
        $this->startAt = $startAt;
        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeImmutable $endAt): ApiConference
    {
        $this->endAt = $endAt;
        return $this;
    }

    public function getVolunteerings(): array
    {
        return $this->volunteerings;
    }

    public function setVolunteerings(array $volunteerings): ApiConference
    {
        $this->volunteerings = $volunteerings;
        return $this;
    }

    public function getOrganizations(): array
    {
        return $this->organizations;
    }

    public function addOrganization(ApiOrganization $organization): ApiConference
    {
        if (!in_array($organization, $this->organizations, true)) {
            $this->organizations[] = $organization;
        }

        return $this;
    }

    public function removeOrganization(ApiOrganization $organization): ApiConference
    {
        if (in_array($organization, $this->organizations, true)) {
            $this->organizations = array_filter($this->organizations, fn($o) => $o !== $organization);
        }

        return $this;
    }
}
