<?php

namespace Tests\Fixtures;

use Closure;
use Laravel\SerializableClosure\SerializableClosure;

class ClassWithTypedClosureProperty
{
    public Closure $callback;

    public function __construct(?Closure $callback = null)
    {
        $this->callback = $callback ?? fn () => 'default';
    }

    public function call(): mixed
    {
        return ($this->callback)();
    }

    public function __serialize(): array
    {
        return ['callback' => new SerializableClosure($this->callback)];
    }

    public function __unserialize(array $data): void
    {
        $this->callback = $data['callback']->getClosure();
    }
}
