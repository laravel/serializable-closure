<?php

use Laravel\SerializableClosure\Support\ReflectionClosure;

test('static function returned from arrow function on same line is parsed correctly', function () {
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

    $result = s($closure);

    expect($result())->toBe('executed');
})->with('serializers');

test('function returned from arrow function on same line is parsed correctly', function () {
    $factory = fn () => function () {
        return 'inner';
    };
    $closure = $factory();

    $reflection = new ReflectionClosure($closure);

    expect($reflection->getCode())->toStartWith('function ()');
    expect($reflection->getCode())->not->toStartWith('fn');
    expect($reflection->isShortClosure())->toBeFalse();
});

test('static function with use clause returned from arrow function serializes correctly', function () {
    $value = 'captured';
    $factory = fn () => static function () use ($value) {
        return $value;
    };
    $closure = $factory();

    $result = s($closure);

    expect($result())->toBe('captured');
})->with('serializers');

test('static function returned from arrow function with parameters serializes correctly', function () {
    $factory = fn ($x) => static function () use ($x) {
        return $x;
    };
    $closure = $factory('test');

    $result = s($closure);

    expect($result())->toBe('test');
})->with('serializers');
