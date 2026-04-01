<?php

use Tests\Fixtures\ClassWithSerializeAndNestedClosures;

/**
 * Regression test for https://github.com/laravel/serializable-closure/issues/126.
 *
 * In v2.0.9, objects implementing __serialize no longer have nested closures
 * wrapped, causing "Serialization of 'Closure' is not allowed" errors.
 * This specifically breaks Bus::chain with closures when Bus::batch is nested inside.
 */
test('objects with __serialize still have nested closures wrapped in outer closure', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'test-chain',
        [fn () => 'step1', 'plain-value', fn () => 'step2'],
        fn () => 'callback'
    );

    // Simulate the pattern: a closure captures an object that has __serialize
    // and that object's array properties contain closures.
    // Before the fix, wrapClosures would skip the entire object, leaving
    // the closures in chainItems unwrapped, causing serialization failure.
    $closure = function () use ($obj) {
        return $obj->name;
    };

    expect(s($closure))->toBeInstanceOf(Closure::class);
    expect(s($closure)())->toBe('test-chain');
})->with('serializers');

test('objects with __serialize have array property closures wrapped when bound via $this', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'bound-chain',
        [fn () => 'bound-step'],
        fn () => 'bound-callback'
    );

    $closure = Closure::bind(function () {
        return $this->name;
    }, $obj, ClassWithSerializeAndNestedClosures::class);

    expect(s($closure))->toBeInstanceOf(Closure::class);
    expect(s($closure)())->toBe('bound-chain');
})->with('serializers');

test('array property closures are callable after round-trip serialization', function () {
    $obj = new ClassWithSerializeAndNestedClosures(
        'round-trip',
        [
            fn () => 'first',
            fn () => 'second',
        ],
        fn () => 'callback'
    );

    $closure = function () use ($obj) {
        return array_map(fn ($cb) => $cb(), $obj->chainItems);
    };

    expect(s($closure)())->toBe(['first', 'second']);
})->with('serializers');
