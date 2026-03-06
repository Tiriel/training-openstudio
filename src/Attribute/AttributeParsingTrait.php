<?php

namespace Tiriel\OpenstudioPhp\Attribute;

use Tiriel\OpenstudioPhp\EventListenerInterface;

trait AttributeParsingTrait
{
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

    abstract public function addListener(string $eventName, callable|EventListenerInterface $listener, int $priority = 0): void;
}