<?php

namespace Tests\Fixtures;

class ClassWithSerializeAndClosureArrayProperty
{
    public string $name = 'test';
    /** @var \Closure[] */
    public array $callbacks = [];

    public function __serialize(): array
    {
        return [
            'name' => $this->name,
            'callbacks' => $this->callbacks,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->name = $data['name'];
        $this->callbacks = $data['callbacks'];
    }
}
