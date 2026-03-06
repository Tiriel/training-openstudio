<?php

namespace Tiriel\OpenstudioPhp\Resolver;

use Psr\Container\ContainerInterface;

class ContainerResolver implements ListenerResolverInterface
{
    use DoResolveEventListenerTrait;

    public function __construct(
        private readonly ContainerInterface $container,
    ) {}

    public function resolve(mixed $listener): callable
    {
        if (\is_string($listener) && $this->container->has($listener)) {
            $listener = $this->container->get($listener);
        }

        return $this->doResolve($listener);
    }
}