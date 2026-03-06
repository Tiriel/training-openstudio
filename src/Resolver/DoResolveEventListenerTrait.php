<?php

namespace Tiriel\OpenstudioPhp\Resolver;

use Tiriel\OpenstudioPhp\EventListenerInterface;

trait DoResolveEventListenerTrait
{
    public function doResolve(callable|EventListenerInterface $listener): callable
    {
        if ($listener instanceof EventListenerInterface) {
            return $listener->handle(...);
        }

        if (!\is_callable($listener)) {
            throw new \InvalidArgumentException('Cannot resolve listener.');
        }

        return $listener;
    }
}