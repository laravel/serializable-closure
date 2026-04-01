<?php

namespace Tests\Fixtures;

use Closure;
use Laravel\SerializableClosure\SerializableClosure;

class ClassWithNullableClosure
{
    public string $name;

    public ?Closure $handler;

    public array $items;

    public function __construct(string $name, ?Closure $handler, array $items)
    {
        $this->name = $name;
        $this->handler = $handler;
        $this->items = $items;
    }

    public function __serialize(): array
    {
        return [
            'name' => $this->name,
            'handler' => $this->handler ? new SerializableClosure($this->handler) : null,
            'items' => $this->items,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->name = $data['name'];
        $this->handler = $data['handler'] ? $data['handler']->getClosure() : null;
        $this->items = $data['items'];
    }
}
