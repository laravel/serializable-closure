<?php

use Tests\Fixtures\ClassWithTypedClosureProperty;

test('respect serialization of typed Closure property via use', function () {
    $obj = new ClassWithTypedClosureProperty(fn () => 'custom');

    $closure = function () use ($obj) {
        return $obj->call();
    };

    expect(s($closure))->toBeInstanceOf(Closure::class);
    expect(s($closure)())->toBe('custom');
})->with('serializers');

test('respect serialization of typed Closure property via $this binding', function () {
    $obj = new ClassWithTypedClosureProperty(fn () => 'from binding');

    $closure = Closure::bind(function () {
        return $this->call();
    }, $obj, ClassWithTypedClosureProperty::class);

    expect(s($closure))->toBeInstanceOf(Closure::class);
    expect(s($closure)())->toBe('from binding');
})->with('serializers');
