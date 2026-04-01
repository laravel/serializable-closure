<?php

namespace Tests\Fixtures;

class ClassWithReadonly
{
    public readonly string $name;

    public array $items;

    public function __construct(string $name, array $items)
    {
        $this->name = $name;
        $this->items = $items;
    }

    public function __serialize(): array
    {
        return ['name' => $this->name, 'items' => $this->items];
    }

    public function __unserialize(array $data): void
    {
        $this->name = $data['name'];
        $this->items = $data['items'];
    }
}
