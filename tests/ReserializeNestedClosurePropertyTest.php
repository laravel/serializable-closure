<?php

use Laravel\SerializableClosure\SerializableClosure;
use Laravel\SerializableClosure\Serializers;
use Laravel\SerializableClosure\UnsignedSerializableClosure;
use Tests\Fixtures\ClassWithSerializableClosureProperty;

/**
 * Regression test for https://github.com/laravel/serializable-closure/issues/161.
 *
 * A closure nested in an object (or stdClass) property is wrapped into a bare
 * Serializers\Native on serialize. On unserialize it must be restored to a real
 * Closure, otherwise the next serialize() treats the Native as a closure and
 * throws "must be of type Closure, ...\Serializers\Native given".
 */
function reserializeWrap($closure, $serializer)
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

test('closure in object property is restored to a real closure and survives re-serialization', function ($serializer) {
    $holder = new ClassWithSerializableClosureProperty(fn () => 'hello');

    $wrapped = reserializeWrap(fn () => $holder, $serializer);

    $wrapped = unserialize(serialize($wrapped));
    $resolved = ($wrapped->getClosure())();

    expect($resolved->closure)->toBeInstanceOf(Closure::class);
    expect(($resolved->closure)())->toBe('hello');

    // Re-serializing must not throw and must keep working.
    $reWrapped = unserialize(serialize($wrapped));
    $reResolved = ($reWrapped->getClosure())();

    expect(($reResolved->closure)())->toBe('hello');
})->with('serializers');

test('closure in stdClass property is restored and survives re-serialization', function ($serializer) {
    $obj = new stdClass();
    $obj->fn = fn () => 'world';

    $wrapped = reserializeWrap(fn () => $obj, $serializer);

    $wrapped = unserialize(serialize($wrapped));
    $resolved = ($wrapped->getClosure())();

    expect($resolved->fn)->toBeInstanceOf(Closure::class);
    expect(($resolved->fn)())->toBe('world');

    $reWrapped = unserialize(serialize($wrapped));
    $reResolved = ($reWrapped->getClosure())();

    expect(($reResolved->fn)())->toBe('world');
})->with('serializers');
