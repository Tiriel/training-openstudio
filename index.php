<?php


use Tiriel\OpenstudioPhp\Attribute\EventListener;
use Tiriel\OpenstudioPhp\EventDispatcher;

require_once __DIR__.'/vendor/autoload.php';

class FooEventListener
{
    #[EventListener('foo')]
    public function onFoo(object $event): void
    {
        echo "Foo event handled\n";
    }
}

$dispatcher = new EventDispatcher();
$dispatcher->addListenersFrom(new FooEventListener());
$dispatcher->dispatch(new class {}, 'foo');
