<?php

namespace Tiriel\OpenstudioPhp;
interface EventListenerInterface
{
    public function handle(object $event): void;
}