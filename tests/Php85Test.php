<?php

test('closure with static function as default parameter value', function () {
    $f = function (callable $handler = static function () {
        return 'fallback';
    }) {
        return $handler();
    };

    $s = s($f);

    expect($s())->toBe('fallback')
        ->and($s(function () {
            return 'custom';
        }))->toBe('custom');
})->with('serializers');

test('static fn with static function as default parameter value', function () {
    $f = static fn (callable $handler = static function () {
        return 'fn-fallback';
    }) => $handler();

    $s = s($f);

    expect($s())->toBe('fn-fallback');
})->with('serializers');

test('closure with multiple static function defaults', function () {
    $f = function (callable $a = static function () {
        return 'A';
    }, callable $b = static function () {
        return 'B';
    }) {
        return $a().$b();
    };

    $s = s($f);

    expect($s())->toBe('AB');
})->with('serializers');

test('closure with static function default that has return type', function () {
    $f = function (callable $handler = static function (): int {
        return 42;
    }) {
        return $handler();
    };

    $s = s($f);

    expect($s())->toBe(42);
})->with('serializers');

test('closure with static function default that has parameters', function () {
    $f = function (callable $handler = static function (int $x = 5): int {
        return $x * 2;
    }) {
        return $handler();
    };

    $s = s($f);

    expect($s())->toBe(10);
})->with('serializers');

test('closure with nested static function in default', function () {
    $f = function (callable $handler = static function () {
        return static function () {
            return 99;
        };
    }) {
        return $handler()();
    };

    $s = s($f);

    expect($s())->toBe(99);
})->with('serializers');

test('closure with mixed regular and static function defaults', function () {
    $f = function (int $x = 10, callable $handler = static function () {
        return 'default';
    }, string $y = 'test') {
        return $x.$handler().$y;
    };

    $s = s($f);

    expect($s())->toBe('10defaulttest');
})->with('serializers');
