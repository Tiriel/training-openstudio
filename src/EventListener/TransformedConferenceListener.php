<?php

namespace App\EventListener;

use App\Entity\Conference;
use App\Event\TransformedConferenceEvent;
use App\Search\Handler\TransformedConferenceHandler;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener]
final class TransformedConferenceListener
{
    public function __construct(
        private readonly TransformedConferenceHandler $handler,
    ) {}

    public function __invoke(TransformedConferenceEvent $event): void
    {
        $dto = $event->getDto();
        $conference = $this->handler->handle($dto);

        if ($conference instanceof Conference) {
            $event->setConference($conference);
        }
    }
}
