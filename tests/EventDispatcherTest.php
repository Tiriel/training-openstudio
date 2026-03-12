<?php

namespace Tiriel\OpenstudioPhp\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tiriel\OpenstudioPhp\AbstractEvent;
use Tiriel\OpenstudioPhp\EventDispatcher;
use Tiriel\OpenstudioPhp\EventListenerInterface;
use Tiriel\OpenstudioPhp\Resolver\InstantiatingResolver;

#[CoversClass(EventDispatcher::class)]
class EventDispatcherTest extends TestCase
{
    private EventDispatcher $dispatcher;

    protected function setUp(): void
    {
        $this->dispatcher ??= new EventDispatcher(new InstantiatingResolver());
    }

    #[Test]
    public function dispatchCallsRegisteredEventListener(): void
    {
        $event = new class extends AbstractEvent {};

        $listener = $this->createMock(EventListenerInterface::class);
        $listener->expects($this->once())
            ->method('handle')
            ->with($this->equalTo($event));

        $this->dispatcher->addListener('test.event', $listener);
        $this->dispatcher->dispatch($event, 'test.event');
    }
}