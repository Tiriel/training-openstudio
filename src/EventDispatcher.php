<?php

namespace Tiriel\OpenstudioPhp;

use Tiriel\OpenstudioPhp\Attribute\EventListener;
use Tiriel\OpenstudioPhp\DataStructure\ListenerHeap;
use Tiriel\OpenstudioPhp\Exception\NoListenersException;

class EventDispatcher
{
    private array $listeners = [];

    public function addListener(string $eventName, callable|EventListenerInterface $listener, int $priority = 0): void
    {
        if (!isset($this->listeners[$eventName])) {
            $this->listeners[$eventName] = new ListenerHeap();
        }

        $this->listeners[$eventName]->insert($listener, $priority);
    }

    public function addListenersFrom(object $listener): void
    {
        $reflection = new \ReflectionClass($listener);

        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            foreach ($method->getAttributes(EventListener::class) as $attribute) {
                /** @var EventListener $eventListener */
                $eventListener = $attribute->newInstance();
                $this->addListener($eventListener->eventName, [$listener, $method->getName()], $eventListener->priority);
            }
        }
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

    protected function doDispatch(callable|EventListenerInterface $listener, object $event): void
    {
        if ($event instanceof AbstractEvent && $event->isPropagationStopped()) {
            return;
        }

        $listener instanceof EventListenerInterface
            ? $listener->handle($event)
            : $listener($event);
    }
}