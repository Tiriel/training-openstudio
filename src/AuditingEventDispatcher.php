<?php

namespace Tiriel\OpenstudioPhp;

use Tiriel\OpenstudioPhp\Attribute\AttributeParsingTrait;
use Tiriel\OpenstudioPhp\Attribute\EventListener;
use Tiriel\OpenstudioPhp\EventDispatcherInterface;

class AuditingEventDispatcher implements EventDispatcherInterface
{
    use AttributeParsingTrait;

    private array $listenerCounts = [];

    public function __construct(
        private readonly EventDispatcherInterface $inner,
    ) {}

    public function addListener(string $eventName, callable|EventListenerInterface $listener, int $priority = 0): void
    {
        echo sprintf('[AUDIT] Added 1 listener to event "%s"%s', $eventName, PHP_EOL);
        $this->listenerCounts[$eventName] = ($this->listenerCounts[$eventName] ?? 0) + 1;
        $this->inner->addListener($eventName, $listener, $priority);
    }

    public function dispatch(object $event, ?string $eventName = null): object
    {
        $eventName ??= $event::class;
        echo sprintf('[AUDIT] "%s" — %d listener(s)%s', $eventName, $this->listenerCounts[$eventName] ?? 0, PHP_EOL);

        return $this->inner->dispatch($event, $eventName);
    }
}