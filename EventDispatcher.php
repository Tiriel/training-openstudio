<?php

use Attribute\EventListener;

class EventDispatcher
{
    private array $listeners = [];

    public function addListener(string $eventName, callable|EventListenerInterface $listener): void
    {
        $this->listeners[$eventName][] = $listener;
    }

    public function addListenersFrom(object $listener): void
    {
        $reflection = new ReflectionClass($listener);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            foreach ($method->getAttributes(EventListener::class) as $attribute) {
                /** @var EventListener $eventListener */
                $eventListener = $attribute->newInstance();
                $this->addListener($eventListener->eventName, [$listener, $method->getName()]);
            }
        }
    }

    public function dispatch(object $event, ?string $eventName = null): object
    {
        $eventName ??= $event::class;

        foreach ($this->listeners[$eventName] as $listener) {
            $listener instanceof EventListenerInterface
                ? $listener->handle($event)
                : $listener($event);
        }

        return $event;
    }
}