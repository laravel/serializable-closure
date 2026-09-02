<?php

use Carbon\CarbonImmutable;
use Tests\Fixtures\ClassWithCarbonProperty;
use Tests\Fixtures\CustomCollection;
use Tests\Fixtures\CustomDate;

/**
 * Regression test for https://github.com/laravel/serializable-closure/issues/109.
 *
 * Objects whose class inherits from a PHP internal class (e.g. Carbon
 * extending DateTimeImmutable) carry internal state that
 * newInstanceWithoutConstructor() cannot rebuild. Reconstructing them
 * property by property produces uninitialized instances that fail with
 * "has not been correctly initialized by calling parent::__construct()".
 *
 * The guard added in v2.0.9 for this was removed in v2.0.11 (#129),
 * reintroducing the crash for Bus::chain closures capturing models
 * with Carbon date attributes.
 */
test('closures capturing objects with carbon properties can be serialized', function () {
    $obj = new ClassWithCarbonProperty(CarbonImmutable::parse('2026-01-01 12:00:00', 'UTC'));

    $closure = function () use ($obj) {
        return $obj->paidAt->toIso8601String();
    };

    expect(s($closure)())->toBe('2026-01-01T12:00:00+00:00');
})->with('serializers');

test('closures bound to objects with carbon properties can be serialized', function () {
    $obj = new ClassWithCarbonProperty(CarbonImmutable::parse('2026-01-01 12:00:00', 'UTC'));

    expect(s($obj->makeClosure())())->toBe('2026-01-01T12:00:00+00:00');
})->with('serializers');

test('userland subclasses of internal classes survive serialization intact', function () {
    $obj = new CustomDate('2026-01-01 12:00:00', new DateTimeZone('Europe/Amsterdam'));

    $closure = function () use ($obj) {
        return $obj->format('Y-m-d H:i:s e');
    };

    expect(s($closure)())->toBe('2026-01-01 12:00:00 Europe/Amsterdam');
})->with('serializers');

test('userland subclasses of internal classes keep their internal storage', function () {
    $obj = new CustomCollection(['a', 'b', 'c']);

    $closure = function () use ($obj) {
        return $obj->getArrayCopy();
    };

    expect(s($closure)())->toBe(['a', 'b', 'c']);
})->with('serializers');

test('carbon instances keep their value and timezone after round trip', function () {
    $obj = new ClassWithCarbonProperty(CarbonImmutable::parse('2026-06-15 08:30:00', 'America/New_York'));

    $closure = function () use ($obj) {
        return $obj->paidAt;
    };

    $restored = s($closure)();

    expect($restored)->toBeInstanceOf(CarbonImmutable::class);
    expect($restored->format('Y-m-d H:i:s'))->toBe('2026-06-15 08:30:00');
    expect($restored->timezoneName)->toBe('America/New_York');
})->with('serializers');
