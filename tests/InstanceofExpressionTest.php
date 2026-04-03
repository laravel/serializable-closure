<?php

use Foo\Bar as Baz;
use Foo\Baz\Qux\Forest;

test('instanceof with parenthesized class reference', function () {
    $f1 = fn (Baz $a) => $this instanceof (Forest::class);
    $e1 = 'fn (\Foo\Bar $a) => $this instanceof (\Foo\Baz\Qux\Forest::class)';

    expect($f1)->toBeCode($e1);
});

test('instanceof with expression', function () {
    $f1 = fn () => $this instanceof (class_exists('Foo') ? \Foo\Bar::class : \Foo\Baz\Qux\Forest::class);
    $e1 = 'fn () => $this instanceof (\class_exists(\'Foo\') ? \Foo\Bar::class : \Foo\Baz\Qux\Forest::class)';

    expect($f1)->toBeCode($e1);
});

test('instanceof without parentheses still works', function () {
    $f1 = fn (Baz $a) => $this instanceof Forest;
    $e1 = 'fn (\Foo\Bar $a) => $this instanceof \Foo\Baz\Qux\Forest';

    expect($f1)->toBeCode($e1);
});

test('instanceof with variable still works', function () {
    $f1 = fn ($class) => $this instanceof $class;
    $e1 = 'fn ($class) => $this instanceof $class';

    expect($f1)->toBeCode($e1);
});
