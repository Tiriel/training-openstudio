<?php

namespace Tiriel\OpenstudioPhp\Exception;

class InvalidListenerClassException extends \InvalidArgumentException
{
    public function __construct(
        public readonly string $classname
    ) {
        parent::__construct("Listener class is not valid. It must be a callable or implement EventListenerInterface.");
    }
}