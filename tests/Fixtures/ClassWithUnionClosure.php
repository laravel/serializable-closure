<?php

namespace Tests\Fixtures;

use Closure;
use Laravel\SerializableClosure\SerializableClosure;

class ClassWithUnionClosure
{
    public string $name;

    public Closure|string $action;

    public array $items;

    public function __construct(string $name, Closure|string $action, array $items)
    {
        $this->name = $name;
        $this->action = $action;
        $this->items = $items;
    }

    public function __serialize(): array
    {
        return [
            'name' => $this->name,
            'action' => $this->action instanceof Closure ? new SerializableClosure($this->action) : $this->action,
            'items' => $this->items,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->name = $data['name'];
        $this->action = $data['action'] instanceof SerializableClosure ? $data['action']->getClosure() : $data['action'];
        $this->items = $data['items'];
    }
}
