<?php

namespace App\Event;

use App\Dto\ApiConference;
use App\Entity\Conference;
use Symfony\Contracts\EventDispatcher\Event;

class TransformedConferenceEvent extends Event
{
    private ?Conference $conference = null;

    public function __construct(
        private readonly ApiConference $dto,
    ) {}

    public function getDto(): ApiConference
    {
        return $this->dto;
    }

    public function hasConference(): bool
    {
        return $this->conference instanceof Conference;
    }

    public function getConference(): ?Conference
    {
        return $this->conference;
    }

    public function setConference(Conference $conference): TransformedConferenceEvent
    {
        $this->conference = $conference;
        return $this;
    }
}
