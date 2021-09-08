<?php

use Foo\{
    Bar as Baz,
};

test('resolve arguments', function () {
    $f1 = function (object $p) {
    };
    $e1 = 'function (object $p) {
    }';

    expect($f1)->toBeCode($e1);
});

test('resolve return type', function () {
    $f1 = function (): object {
    };
    $e1 = 'function (): object {
    }';

    expect($f1)->toBeCode($e1);
});

test('trailing comma', function () {
    $f1 = function (): Baz {
    };
    $e1 = 'function (): \Foo\Bar {
    }';

    expect($f1)->toBeCode($e1);
});
