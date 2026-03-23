<?php

namespace App\Dto;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class Account implements EntityCastableInterface
{
    #[Assert\NotBlank(groups: ['account'])]
    #[Assert\Email(groups: ['account'])]
    public ?string $email = null;

    #[Assert\NotBlank(groups: ['account'])]
    #[Assert\Length(
        min: 6,
        max: 4096,
        minMessage: 'Your password should be at least {{ limit }} characters'
    )]
    #[Assert\NotCompromisedPassword(groups: ['account'])]
    public ?string $plainPassword = null;

    public function toEntity(): User
    {
        return (new User())
            ->setEmail($this->email)
            ->setPassword($this->plainPassword)
        ;
    }
}
