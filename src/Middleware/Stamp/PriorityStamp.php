<?php

namespace App\Middleware\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

class PriorityStamp implements StampInterface
{
    public function __construct(
        public readonly int $priority
    ) {}
}
