<?php

namespace Tests\Fixtures;

class ClassWithCircularRef
{
    public string $name;

    public array $items;

    public ?self $self = null;

    public function __construct(string $name, array $items)
    {
        $this->name = $name;
        $this->items = $items;
    }

    public function __serialize(): array
    {
        return ['name' => $this->name, 'items' => $this->items, 'self' => $this->self];
    }

    public function __unserialize(array $data): void
    {
        $this->name = $data['name'];
        $this->items = $data['items'];
        $this->self = $data['self'];
    }
}
