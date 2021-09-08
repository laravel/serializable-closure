<?php

use Laravel\SerializableClosure\Serializers;
use Opis\Closure\SerializableClosure as BaseSerializableClosure;

dataset('serializers', function () {
    $serializers = (float) phpversion() <= '8.0'
        ? [BaseSerializableClosure::class]
        : [];

    $serializers = array_merge($serializers, [
        Serializers\Native::class,
        Serializers\Signed::class,
    ]);
    foreach ($serializers as $serializer) {
        yield function () use ($serializer) {
            $this->serializer = $serializer;
        };
    }
});
