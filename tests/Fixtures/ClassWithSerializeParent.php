<?php

namespace Tests\Fixtures;

use Closure;
use Laravel\SerializableClosure\SerializableClosure;

class ClassWithSerializeParent
{
    public string $parentName;

    public Closure $parentCallback;

    public function __construct(string $name, Closure $cb)
    {
        $this->parentName = $name;
        $this->parentCallback = $cb;
    }

    public function __serialize(): array
    {
        return [
            'parentName' => $this->parentName,
            'parentCallback' => new SerializableClosure($this->parentCallback),
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->parentName = $data['parentName'];
        $this->parentCallback = $data['parentCallback']->getClosure();
    }
}
