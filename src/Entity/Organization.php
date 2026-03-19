<?php

namespace App\Entity;

use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
class Organization
{
    #[Groups(['conference:list', 'conference:get'])]
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[Groups(['conference:list', 'conference:get'])]
    #[Assert\Length(min: 10)]
    #[Assert\NotBlank()]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['conference:get'])]
    #[Assert\Length(min: 30)]
    #[Assert\NotBlank()]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $presentation = null;

    #[Groups(['conference:get'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Conference>
     */
    #[ORM\ManyToMany(targetEntity: Conference::class, inversedBy: 'organizations')]
    private Collection $conferences;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'organizations')]
    private Collection $users;

    public function __construct()
    {
        $this->conferences = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): static
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Conference>
     */
    public function getConferences(): Collection
    {
        return $this->conferences;
    }

    public function addConference(Conference $conference): static
    {
        if (!$this->conferences->contains($conference)) {
            $this->conferences->add($conference);
        }

        return $this;
    }

    public function removeConference(Conference $conference): static
    {
        $this->conferences->removeElement($conference);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addOrganization($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeOrganization($this);
        }

        return $this;
    }
}
