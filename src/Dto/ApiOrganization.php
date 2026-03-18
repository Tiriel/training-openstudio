<?php

namespace App\Dto;

use App\Entity\Organization;
use Symfony\Component\Uid\Uuid;

class ApiOrganization
{
    private ?Uuid $id = null;
    private ?string $name = null;
    private ?string $presentation = null;
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(?Uuid $id): ApiOrganization
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): ApiOrganization
    {
        $this->name = $name;
        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): ApiOrganization
    {
        $this->presentation = $presentation;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): ApiOrganization
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function toEntity(): Organization
    {
        return (new Organization())
            ->setName($this->name)
            ->setPresentation($this->presentation)
            ->setCreatedAt($this->createdAt ?? new \DateTimeImmutable());
    }
}
