<?php

use Laravel\SerializableClosure\Support\ReflectionClosure;

test('static function returned from arrow function is parsed correctly', function () {
    $factory = fn () => static function () {
        return 'inner';
    };
    $closure = $factory();

    $reflection = new ReflectionClosure($closure);

    expect($reflection->getCode())->toStartWith('static function ()');
    expect($reflection->getCode())->not->toStartWith('fn');
    expect($reflection->isShortClosure())->toBeFalse();
});

test('static function returned from arrow function serializes correctly', function () {
    $factory = fn () => static function () {
        return 'executed';
    };
    $closure = $factory();

    expect(s($closure)())->toBe('executed');
})->with('serializers');

test('function returned from arrow function is parsed correctly', function () {
    $factory = fn () => function () {
        return 'inner';
    };
    $closure = $factory();

    $reflection = new ReflectionClosure($closure);

    expect($reflection->getCode())->toStartWith('function ()');
    expect($reflection->getCode())->not->toStartWith('fn');
    expect($reflection->isShortClosure())->toBeFalse();
});

test('function returned from arrow function serializes correctly', function () {
    $factory = fn () => function () {
        return 'hello';
    };
    $closure = $factory();

    expect(s($closure)())->toBe('hello');
})->with('serializers');

test('arrow function returned from arrow function serializes correctly', function () {
    $factory = fn () => fn () => 'inner';
    $closure = $factory();

    expect(s($closure)())->toBe('inner');
})->with('serializers');

test('function with use clause returned from arrow function serializes correctly', function () {
    $value = 'captured';
    $factory = fn () => static function () use ($value) {
        return $value;
    };
    $closure = $factory();

    expect(s($closure)())->toBe('captured');
})->with('serializers');

test('function returned from arrow function with parameters serializes correctly', function () {
    $factory = fn ($x) => static function () use ($x) {
        return $x;
    };
    $closure = $factory('test');

    expect(s($closure)())->toBe('test');
})->with('serializers');

test('standalone arrow function still works', function () {
    $fn = fn () => 42;

    expect(s($fn)())->toBe(42);
})->with('serializers');

test('standalone function still works', function () {
    $fn = function () {
        return 42;
    };

    expect(s($fn)())->toBe(42);
})->with('serializers');
