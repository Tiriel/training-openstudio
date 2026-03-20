<?php

namespace App\Dto;

use App\Entity\User;

class Account implements EntityCastableInterface
{
    public ?string $email = null;

    public ?string $plainPassword = null;
    public function toEntity(): User
    {
        // TODO: Implement toEntity() method.
    }
}
