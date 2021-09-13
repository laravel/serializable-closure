<?php

use Laravel\SerializableClosure\Serializers;

dataset('serializers', function () {
    foreach ([Serializers\Native::class, Serializers\Signed::class] as $serializer) {
        yield (new ReflectionClass($serializer))->getShortName() => function () use ($serializer) {
            $this->serializer = $serializer;
        };
    }
});
