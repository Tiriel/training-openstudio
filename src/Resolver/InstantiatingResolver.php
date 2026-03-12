<?php

namespace Tiriel\OpenstudioPhp\Resolver;

use Tiriel\OpenstudioPhp\Resolver\ListenerResolverInterface;

class InstantiatingResolver implements ListenerResolverInterface
{
    use DoResolveEventListenerTrait;

    public function resolve(mixed $listener): callable
    {
        if (\is_string($listener) && !\class_exists($listener)) {
            throw new \InvalidArgumentException("Class not found");
        } elseif (\is_string($listener) && \class_exists($listener)) {
            $listener = new $listener();
        }

        return $this->doResolve($listener);
    }
}