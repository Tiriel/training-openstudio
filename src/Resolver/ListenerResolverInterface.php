<?php

namespace Tiriel\OpenstudioPhp\Resolver;

interface ListenerResolverInterface
{
    public function resolve(mixed $listener): callable;
}