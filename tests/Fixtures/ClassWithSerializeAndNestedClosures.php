<?php

namespace Tests\Fixtures;

use Closure;
use Laravel\SerializableClosure\SerializableClosure;

/**
 * Simulates a class like Laravel's ChainedBatch that has __serialize
 * but also contains non-Closure properties with nested closures
 * (e.g., an array of chain items that may include closures).
 *
 * The __serialize method handles the Closure-typed property itself,
 * but the array property containing closures relies on the library
 * to wrap them before serialization.
 */
class ClassWithSerializeAndNestedClosures
{
    public string $name;

    /** @var array<int, mixed> */
    public array $chainItems;

    public Closure $callback;

    public function __construct(string $name, array $chainItems, Closure $callback)
    {
        $this->name = $name;
        $this->chainItems = $chainItems;
        $this->callback = $callback;
    }

    public function __serialize(): array
    {
        return [
            'name' => $this->name,
            'chainItems' => $this->chainItems,
            'callback' => new SerializableClosure($this->callback),
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->name = $data['name'];
        $this->chainItems = $data['chainItems'];
        $this->callback = $data['callback']->getClosure();
    }
}
