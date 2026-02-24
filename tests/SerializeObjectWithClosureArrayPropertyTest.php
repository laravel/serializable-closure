<?php

use Tests\Fixtures\ClassWithSerializeAndClosureArrayProperty;

test('closure use variable referencing object with __serialize and closure array property', function () {
    $obj = new ClassWithSerializeAndClosureArrayProperty();
    $obj->callbacks[] = fn () => 'callback result';

    $closure = function () use ($obj) {
        return $obj->name;
    };

    expect(s($closure)())->toBe('test');
})->with('serializers');

test('closure bound to object with __serialize and closure array property', function () {
    $obj = new ClassWithSerializeAndClosureArrayProperty();
    $obj->callbacks[] = fn () => 'callback result';

    $closure = Closure::bind(function () {
        return $this->name;
    }, $obj, ClassWithSerializeAndClosureArrayProperty::class);

    expect(s($closure)())->toBe('test');
})->with('serializers');

test('closure use variable referencing object with __serialize preserves closure array values', function () {
    $obj = new ClassWithSerializeAndClosureArrayProperty();
    $obj->callbacks[] = fn () => 'first';
    $obj->callbacks[] = fn () => 'second';

    $closure = function () use ($obj) {
        return array_map(fn ($cb) => $cb(), $obj->callbacks);
    };

    expect(s($closure)())->toBe(['first', 'second']);
})->with('serializers');
