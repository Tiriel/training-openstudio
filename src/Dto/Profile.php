<?php

namespace App\Dto;

use App\Entity\VolunteerProfile;
use Symfony\Component\Validator\Constraints as Assert;

class Profile implements EntityCastableInterface
{
    #[Assert\Valid(groups: ['profile'])]
    public array $skills = [];

    #[Assert\Valid(groups: ['profile'])]
    public array $interests = [];

    public function toEntity(): VolunteerProfile
    {
        $profile = new VolunteerProfile();
        foreach ($this->skills as $skill) {
            $profile->addSkill($skill);
        }

        foreach ($this->interests as $tag) {
            $profile->addInterest($tag);
        }

        return $profile;
    }
}
