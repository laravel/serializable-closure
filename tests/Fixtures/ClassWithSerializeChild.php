<?php

namespace Tests\Fixtures;

use Closure;

class ClassWithSerializeChild extends ClassWithSerializeParent
{
    public array $childItems;

    public function __construct(string $name, Closure $cb, array $items)
    {
        parent::__construct($name, $cb);
        $this->childItems = $items;
    }

    public function __serialize(): array
    {
        return array_merge(parent::__serialize(), ['childItems' => $this->childItems]);
    }

    public function __unserialize(array $data): void
    {
        parent::__unserialize($data);
        $this->childItems = $data['childItems'];
    }
}
