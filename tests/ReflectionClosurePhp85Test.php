<?php

test('closure with static function default parameter', function () {
    $f = function (callable $handler = static function () {
        return 'fallback';
    }) {
        return $handler();
    };
    $e = 'function (callable $handler = static function () {
        return \'fallback\';
    }) {
        return $handler();
    }';
    expect($f)->toBeCode($e);
});

test('closure with static function default that has return type', function () {
    $f = function (callable $handler = static function (): int {
        return 42;
    }) {
        return $handler();
    };
    $e = 'function (callable $handler = static function (): int {
        return 42;
    }) {
        return $handler();
    }';
    expect($f)->toBeCode($e);
});

test('closure with multiple static function default parameters', function () {
    $f = function (callable $a = static function () {
        return 1;
    }, callable $b = static function () {
        return 2;
    }) {
        return $a() + $b();
    };
    $e = 'function (callable $a = static function () {
        return 1;
    }, callable $b = static function () {
        return 2;
    }) {
        return $a() + $b();
    }';
    expect($f)->toBeCode($e);
});

test('closure with nested function in static function default body', function () {
    $f = function (callable $handler = static function () {
        return static function () {
            return 'inner';
        };
    }) {
        return $handler()();
    };
    $e = 'function (callable $handler = static function () {
        return static function () {
            return \'inner\';
        };
    }) {
        return $handler()();
    }';
    expect($f)->toBeCode($e);
});

test('closure with string interpolation in static function default', function () {
    $f = function (callable $handler = static function () {
        $x = 'world';

        return "hello {$x}";
    }) {
        return $handler();
    };
    $e = 'function (callable $handler = static function () {
        $x = \'world\';

        return "hello {$x}";
    }) {
        return $handler();
    }';
    expect($f)->toBeCode($e);
});

test('closure with mixed regular and default closure parameters', function () {
    $f = function (int $x, callable $handler = static function () {
        return 'fallback';
    }, string $y = 'test') {
        return $handler();
    };
    $e = 'function (int $x, callable $handler = static function () {
        return \'fallback\';
    }, string $y = \'test\') {
        return $handler();
    }';
    expect($f)->toBeCode($e);
});
