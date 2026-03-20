<?php

namespace App\Message;

use App\Entity\Conference;
use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[AsMessage('high_priority')]
final class CreateVolunteerCommand
{

    public function __construct(
        public int $userId,
        public ?Uuid $conferenceId = null,
        #[Assert\GreaterThanOrEqual('today')]
        public ?\DateTimeImmutable $startAt = null,
        #[Assert\GreaterThanOrEqual(propertyPath: 'startAt')]
        public ?\DateTimeImmutable $endAt = null,
    ) {}
}
