<?php


use Tiriel\OpenstudioPhp\Attribute\EventListener;
use Tiriel\OpenstudioPhp\EventDispatcher;
use Tiriel\OpenstudioPhp\Exception\NoListenersException;

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

try {
    $dispatcher->dispatch(new class {
    }, 'bar');
} catch (NoListenersException $e) {
    echo sprintf("%s Event : %s\n", $e->getMessage(), $e->eventName);
}
