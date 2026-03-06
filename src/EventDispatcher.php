<?php

namespace Tiriel\OpenstudioPhp;

use Tiriel\OpenstudioPhp\Attribute\AttributeParsingTrait;
use Tiriel\OpenstudioPhp\Attribute\EventListener;
use Tiriel\OpenstudioPhp\DataStructure\ListenerHeap;
use Tiriel\OpenstudioPhp\Exception\NoListenersException;

final class EventDispatcher implements EventDispatcherInterface
{
    use AttributeParsingTrait;

    private array $listeners = [];

    public function addListener(string $eventName, callable|EventListenerInterface $listener, int $priority = 0): void
    {
        if (!isset($this->listeners[$eventName])) {
            $this->listeners[$eventName] = new ListenerHeap();
        }

        $this->listeners[$eventName]->insert($listener, $priority);
    }

    public function dispatch(object $event, ?string $eventName = null): object
    {
        $eventName ??= $event::class;

        if (!\array_key_exists($eventName, $this->listeners)) {
            throw new NoListenersException($eventName);
        }

        foreach ($this->listeners[$eventName] as $listener) {
            $this->doDispatch($listener, $event);
        }

        return $event;
    }

    private function doDispatch(callable|EventListenerInterface $listener, object $event): void
    {
        if ($event instanceof AbstractEvent && $event->isPropagationStopped()) {
            return;
        }

        $listener instanceof EventListenerInterface
            ? $listener->handle($event)
            : $listener($event);
    }
}