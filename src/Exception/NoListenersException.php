<?php

namespace Tiriel\OpenstudioPhp\Exception;

class NoListenersException extends \InvalidArgumentException
{
    public function __construct(public readonly string $eventName)
    {
        parent::__construct('No listeners found for this event.');
    }
}