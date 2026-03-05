<?php

namespace Tiriel\OpenstudioPhp\DataStructure;

class ListenerHeap extends \SplMaxHeap
{
    private array $storage = [];

    protected function compare(mixed $value1, mixed $value2): int
    {
        return $value1['priority'] <=> $value2['priority'];
    }

    public function insert(mixed $value, int $priority = 0): bool
    {
        $entry = ['listener' => $value, 'priority' => $priority];
        $this->storage[] = $entry;

        return parent::insert($entry);
    }

    public function rewind(): void
    {
        while(!$this->isEmpty()) {
            $this->extract();
        }

        foreach ($this->storage as $entry) {
            parent::insert($entry);
        }

        parent::rewind();
    }

    public function current(): mixed
    {
        return parent::current()['listener'];
    }
}