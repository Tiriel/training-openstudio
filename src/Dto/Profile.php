<?php

namespace App\Dto;

use App\Entity\VolunteerProfile;

class Profile implements EntityCastableInterface
{
    public array $skills = [];
    public array $interests = [];
    public function toEntity(): VolunteerProfile
    {
        //
    }
}
