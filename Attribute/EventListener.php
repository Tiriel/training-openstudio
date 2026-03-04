<?php

namespace Attribute;

#[\Attribute(\Attribute::TARGET_METHOD)]
class EventListener
{
    public function __construct(
        public readonly string $eventName,
        public readonly int $priority = 0,
    ) {}
}