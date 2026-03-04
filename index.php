<?php


require __DIR__.'/EventListenerInterface.php';
require __DIR__.'/EventDispatcher.php';

$dispatcher = new EventDispatcher();
$dispatcher->addListener('foo', new class implements EventListenerInterface {
    public function handle(object $event): void
    {
        echo "Event handled: " . get_class($event) . "\n";
    }
});

$dispatcher->dispatch(new class {}, 'foo');
