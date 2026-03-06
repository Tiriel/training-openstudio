<?php

namespace Tiriel\OpenstudioPhp;

interface EventDispatcherInterface
{
    public function addListener(string $eventName, callable|EventListenerInterface|string $listener, int $priority = 0): void;

    public function addListenersFrom(object $listener): void;

    public function dispatch(object $event, ?string $eventName = null): object;
}