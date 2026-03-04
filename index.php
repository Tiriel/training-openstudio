<?php


require __DIR__.'/EventDispatcher.php';

$dispatcher = new EventDispatcher();
$dispatcher->addListener('foo', function () {
    echo 'foo event triggered'.\PHP_EOL;
});

$dispatcher->dispatch(new class {}, 'foo');
