<?php


use Tiriel\OpenstudioPhp\AbstractEvent;
use Tiriel\OpenstudioPhp\Attribute\EventListener;
use Tiriel\OpenstudioPhp\EventDispatcher;
use Tiriel\OpenstudioPhp\Exception\NoListenersException;

require_once __DIR__.'/vendor/autoload.php';

class FooEventListener
{
    #[EventListener('foo')]
    public function onFooLate(object $event): void
    {
        echo "Foo event handled lately (priority 0)\n";
    }

    #[EventListener('foo', priority: 300)]
    public function onFooBefore(object $event): void
    {
        echo "Foo event handled (priority 300)\n";
    }

    #[EventListener('foo', priority: 200)]
    public function onFoo(object $event): void
    {
        if ($event instanceof AbstractEvent) {
            $event->stopPropagation();
        }

        echo "Foo event handled (priority 200)\n";
    }
}

$dispatcher = new EventDispatcher();
$dispatcher->addListenersFrom(new FooEventListener());

try {
    $dispatcher->dispatch(new class extends AbstractEvent {
    }, 'foo');
} catch (NoListenersException $e) {
    echo sprintf("%s Event : %s\n", $e->getMessage(), $e->eventName);
}
