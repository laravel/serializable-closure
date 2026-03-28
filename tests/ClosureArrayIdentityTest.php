<?php

use Laravel\SerializableClosure\SerializableClosure;

test('multiple arrow closures in an array preserve identity after roundtrip', function () {
    $closures = [fn () => 'a', fn () => 'b', fn () => 'c'];

    $serialized = serialize(array_map(fn ($c) => new SerializableClosure($c), $closures));
    $unserialized = unserialize($serialized);

    expect($unserialized[0]())->toBe('a');
    expect($unserialized[1]())->toBe('b');
    expect($unserialized[2]())->toBe('c');
});

test('two arrow closures on the same line with identical signatures', function () {
    $closures = [fn () => 1, fn () => 2];

    $serialized = serialize(array_map(fn ($c) => new SerializableClosure($c), $closures));
    $unserialized = unserialize($serialized);

    expect($unserialized[0]())->toBe(1);
    expect($unserialized[1]())->toBe(2);
});

test('closures on different lines still work after roundtrip', function () {
    $a = fn () => 'x';
    $b = fn () => 'y';
    $c = fn () => 'z';

    $closures = [$a, $b, $c];
    $serialized = serialize(array_map(fn ($c) => new SerializableClosure($c), $closures));
    $unserialized = unserialize($serialized);

    expect($unserialized[0]())->toBe('x');
    expect($unserialized[1]())->toBe('y');
    expect($unserialized[2]())->toBe('z');
});

test('single closure still works normally after roundtrip', function () {
    $closure = fn () => 'hello';

    $serialized = serialize(new SerializableClosure($closure));
    $unserialized = unserialize($serialized);

    expect($unserialized())->toBe('hello');
});

test('same-line closures with parameters preserve identity', function () {
    $closures = [fn ($x) => $x * 2, fn ($x) => $x * 3, fn ($x) => $x + 1];

    $serialized = serialize(array_map(fn ($c) => new SerializableClosure($c), $closures));
    $unserialized = unserialize($serialized);

    expect($unserialized[0](5))->toBe(10);
    expect($unserialized[1](5))->toBe(15);
    expect($unserialized[2](5))->toBe(6);
});

test('same-line static arrow closures preserve identity', function () {
    $closures = [static fn () => 'first', static fn () => 'second'];

    $serialized = serialize(array_map(fn ($c) => new SerializableClosure($c), $closures));
    $unserialized = unserialize($serialized);

    expect($unserialized[0]())->toBe('first');
    expect($unserialized[1]())->toBe('second');
});

test('mixed signature closures on same line still disambiguate correctly', function () {
    $closures = [fn () => 'no-args', fn ($a) => $a, fn () => 'also-no-args'];

    $serialized = serialize(array_map(fn ($c) => new SerializableClosure($c), $closures));
    $unserialized = unserialize($serialized);

    expect($unserialized[0]())->toBe('no-args');
    expect($unserialized[1]('test'))->toBe('test');
    expect($unserialized[2]())->toBe('also-no-args');
});
