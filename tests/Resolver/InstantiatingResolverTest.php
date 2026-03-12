<?php

namespace Tiriel\OpenstudioPhp\Tests\Resolver;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Tiriel\OpenstudioPhp\EventListenerInterface;
use Tiriel\OpenstudioPhp\Resolver\InstantiatingResolver;
use Tiriel\OpenstudioPhp\Tests\Resolver\Fixtures\InvokableEventListener;
use Tiriel\OpenstudioPhp\Tests\Resolver\Fixtures\HandleEventListener;
use Tiriel\OpenstudioPhp\Tests\Resolver\Fixtures\NonCallableListener;

#[CoversClass(InstantiatingResolver::class)]
class InstantiatingResolverTest extends TestCase
{
    protected InstantiatingResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver ??= new InstantiatingResolver();
    }

    #[Test]
    public function resolveReturnsCallable(): void
    {
        $listener = function ($event) {};

        $result = $this->resolver->resolve($listener);

        $this->assertIsCallable($result);
    }

    #[Test]
    #[TestDox('Resolve wraps EventListenerInterface as Closures')]
    public function resolveWrapsEventListenerInterfaceAsClosures(): void
    {
        $listener = new HandleEventListener();

        $result = $this->resolver->resolve($listener);

        $this->assertInstanceOf(\Closure::class, $result);
    }
    
    #[Test]
    public function resolveInstantiatesExistingInvokableClass(): void
    {
        $listener = InvokableEventListener::class;

        $result = $this->resolver->resolve($listener);

        $this->assertIsCallable($result);
    }

    #[Test]
    public function resolveThrowsOnNonCallableClassname(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $listener = NonCallableListener::class;

        $this->resolver->resolve($listener);
    }

    #[Test]
    public function resolveThrowsOnNonExistentClass(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $listener = 'NonExistentClass';

        $this->resolver->resolve($listener);
    }
}