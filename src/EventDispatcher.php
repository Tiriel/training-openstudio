<?php

namespace Tiriel\OpenstudioPhp;

use Tiriel\OpenstudioPhp\Attribute\AttributeParsingTrait;
use Tiriel\OpenstudioPhp\Attribute\EventListener;
use Tiriel\OpenstudioPhp\DataStructure\ListenerHeap;
use Tiriel\OpenstudioPhp\Exception\InvalidListenerClassException;
use Tiriel\OpenstudioPhp\Exception\NoListenersException;
use Tiriel\OpenstudioPhp\Resolver\ListenerResolverInterface;

final class EventDispatcher implements EventDispatcherInterface
{
    use AttributeParsingTrait;

    private array $listeners = [];

    public function __construct(
        private readonly ListenerResolverInterface $resolver,
    ) {
    }

    public function addListener(string $eventName, callable|EventListenerInterface|string $listener, int $priority = 0): void
    {
        if (\is_string($listener) && !\class_exists($listener)) {
            throw new InvalidListenerClassException($listener);
        }

        $this->listeners[$eventName][$priority][] = $listener;
    }

    public function dispatch(object $event, ?string $eventName = null): object
    {
        $eventName ??= $event::class;

        if (!\array_key_exists($eventName, $this->listeners)) {
            throw new NoListenersException($eventName);
        }

        krsort($this->listeners[$eventName]);

        foreach ($this->listeners[$eventName] as $sortedListeners) {
            foreach ($sortedListeners as $listener) {
                $this->doDispatch($listener, $event);
            }
        }

        return $event;
    }

    private function doDispatch(callable|EventListenerInterface|string $listener, object $event): void
    {
        if ($event instanceof AbstractEvent && $event->isPropagationStopped()) {
            return;
        }

        $listener = $this->resolver->resolve($listener);
        $listener($event);
    }
}