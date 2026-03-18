<?php

namespace App\Search\Decorator;

use App\Event\TransformedConferenceEvent;
use App\Search\Client\ApiConferenceSearch;
use App\Search\Interface\ConferenceSearchInterface;
use App\Search\Transformer\ApiConferenceTransformer;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[AsDecorator(ApiConferenceSearch::class, priority: 10)]
class TransformerApiConferenceSearch implements ConferenceSearchInterface
{
    public function __construct(
        private readonly ConferenceSearchInterface $inner,
        private readonly ApiConferenceTransformer $transformer,
        private readonly EventDispatcherInterface $dispatcher,
    ) {}

    public function searchByName(?string $name = null): array
    {
        $conferences = $this->inner->searchByName($name);

        return \array_map(
            function (array $conf) {
                $dto = $this->transformer->transform($conf);
                $event = new TransformedConferenceEvent($dto);
                $this->dispatcher->dispatch($event);

                return $event->hasConference() ? $event->getConference() : $dto;
            },
            $conferences
        );
    }
}
