<?php

namespace Foo\Bar;

// PHP 8.2+ added `true` as a standalone type (complement of `false`).
// Without the fix, `true` gets treated as a class name and namespace-prefixed
// when the closure is inside a namespace.

test('true type hint is not namespace-prefixed', function () {
    $f1 = fn (): true => true;
    $e1 = 'fn (): true => true';

    expect($f1)->toBeCode($e1);
});

test('true in union type is not namespace-prefixed', function () {
    $f1 = fn (): string|true => true;
    $e1 = 'fn (): string|true => true';

    expect($f1)->toBeCode($e1);
});

test('true as parameter type is not namespace-prefixed', function () {
    $f1 = fn (true $a) => $a;
    $e1 = 'fn (true $a) => $a';

    expect($f1)->toBeCode($e1);
});
