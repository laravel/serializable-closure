<?php

use Laravel\SerializableClosure\SerializableClosure;
use Laravel\SerializableClosure\Serializers;
use Laravel\SerializableClosure\UnsignedSerializableClosure;

/**
 * Regression test for a SerializableClosure nested inside an array that lives
 * on an object property. Fixing #161 introduced a side effect where such a
 * nested SerializableClosure gets replaced by a plain Closure on unserialize,
 * which then fails to serialize again ("Serialization of 'Closure' is not
 * allowed"), because a SerializableClosure always needs to stay wrapped.
 */
class ObjectWithCallbacksProperty
{
    public $callbacks = [];
}

function wrapWith($closure, $serializer)
{
    switch ($serializer) {
        case Serializers\Signed::class:
            SerializableClosure::setSecretKey('secret');

            return new SerializableClosure($closure);
        case UnsignedSerializableClosure::class:
            return SerializableClosure::unsigned($closure);
        default:
            return new SerializableClosure($closure);
    }
}

test('SerializableClosure nested in an array property stays a SerializableClosure after unserialize', function ($serializer) {
    $holder = new ObjectWithCallbacksProperty();
    $holder->callbacks = [
        wrapWith(fn () => 'hello', $serializer),
    ];

    $wrapped = wrapWith(fn () => $holder, $serializer);

    $wrapped = unserialize(serialize($wrapped));
    $resolved = ($wrapped->getClosure())();

    expect($resolved->callbacks[0])->toBeInstanceOf($serializer === UnsignedSerializableClosure::class ? UnsignedSerializableClosure::class : SerializableClosure::class);
    expect($resolved->callbacks[0]->getClosure()())->toBe('hello');

    // Must still be serializable a second time.
    $reWrapped = unserialize(serialize($wrapped));
    $reResolved = ($reWrapped->getClosure())();

    expect($reResolved->callbacks[0]->getClosure()())->toBe('hello');
})->with('serializers');
