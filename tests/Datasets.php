<?php

use Laravel\SerializableClosure\Serializers;
use Laravel\SerializableClosure\UnsignedSerializableClosure;

dataset('serializers', function () {
    foreach ([Serializers\Native::class, Serializers\Signed::class, UnsignedSerializableClosure::class] as $serializer) {
        $serializerShortName = (new ReflectionClass($serializer))->getShortName();

        if ($serializer != UnsignedSerializableClosure::class) {
            $serializerShortName = 'SerializableClosure > '.$serializerShortName;
        }

        yield $serializerShortName => function () use ($serializer) {
            $this->serializer = $serializer;
        };
    }
});
