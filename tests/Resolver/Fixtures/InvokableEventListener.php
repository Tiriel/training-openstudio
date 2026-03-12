<?php

namespace Tiriel\OpenstudioPhp\Tests\Resolver\Fixtures;

class InvokableEventListener
{
    public function __invoke()
    {
        echo "Foo event handled\n";
    }
}