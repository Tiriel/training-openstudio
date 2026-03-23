<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class Registration implements EntityCastableInterface
{
    #[Assert\Valid(groups: ['account'])]
    public ?Account $account = null;

    #[Assert\Valid(groups: ['profile'])]
    public ?Profile $profile = null;

    #[Assert\NotBlank(groups: ['confirmation'])]
    #[Assert\IsTrue(groups: ['confirmation'])]
    public bool $agreeTerms = false;

    public string $currentStep = 'account';

    public function toEntity(): object
    {
        return $this->account->toEntity()
            ->setProfile($this->profile->toEntity());
    }
}
