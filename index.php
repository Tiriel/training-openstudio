<?php


require __DIR__.'/Attribute/EventListener.php';
require __DIR__.'/EventListenerInterface.php';
require __DIR__.'/EventDispatcher.php';

class FooEventListener
{
    #[Attribute\EventListener('foo')]
    public function onFoo(object $event): void
    {
        echo "Foo event handled\n";
    }
}

$dispatcher = new EventDispatcher();
$dispatcher->addListenersFrom(new FooEventListener());
$dispatcher->dispatch(new class {}, 'foo');
