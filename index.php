<?php


use Tiriel\OpenstudioPhp\AbstractEvent;
use Tiriel\OpenstudioPhp\Attribute\EventListener;
use Tiriel\OpenstudioPhp\AuditingEventDispatcher;
use Tiriel\OpenstudioPhp\EventDispatcher;
use Tiriel\OpenstudioPhp\Exception\NoListenersException;
use Tiriel\OpenstudioPhp\Resolver\InstantiatingResolver;

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

class BarEventListener
{
    public function __invoke(object $event): void
    {
        echo "Bar event handled\n";
    }
}

$dispatcher = new AuditingEventDispatcher(new EventDispatcher(new InstantiatingResolver()));
$dispatcher->addListenersFrom(new FooEventListener());
$dispatcher->addListener('bar', BarEventListener::class);

try {
    $dispatcher->dispatch(new class extends AbstractEvent {
    }, 'foo');
} catch (NoListenersException $e) {
    echo sprintf("%s Event : %s\n", $e->getMessage(), $e->eventName);
}
try {
    $dispatcher->dispatch(new class extends AbstractEvent {
    }, 'bar');
} catch (NoListenersException $e) {
    echo sprintf("%s Event : %s\n", $e->getMessage(), $e->eventName);
}
