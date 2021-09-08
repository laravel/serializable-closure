<?php

// Fake
use Foo\Bar;
use Foo\Baz as Qux;

test('resolve arguments', function () {
    $f1 = function (?Bar $p) {
    };
    $e1 = 'function (?\Foo\Bar $p) {
    }';

    $f2 = function (?Bar\Test $p) {
    };
    $e2 = 'function (?\Foo\Bar\Test $p) {
    }';

    $f3 = function (?Qux $p) {
    };
    $e3 = 'function (?\Foo\Baz $p) {
    }';

    $f4 = function (?Qux\Test $p) {
    };
    $e4 = 'function (?\Foo\Baz\Test $p) {
    }';

    $f5 = function (?array $p, ?string $x) {
    };
    $e5 = 'function (?array $p, ?string $x) {
    }';

    expect($f1)->toBeCode($e1);
    expect($f2)->toBeCode($e2);
    expect($f3)->toBeCode($e3);
    expect($f4)->toBeCode($e4);
    expect($f5)->toBeCode($e5);
});

test('resolve return type', function () {
    $f1 = function (): ?Bar {
    };
    $e1 = 'function (): ?\Foo\Bar {
    }';

    $f2 = function (): ?Bar\Test {
    };
    $e2 = 'function (): ?\Foo\Bar\Test {
    }';

    $f3 = function (): ?Qux {
    };
    $e3 = 'function (): ?\Foo\Baz {
    }';

    $f4 = function (): ?Qux\Test {
    };
    $e4 = 'function (): ?\Foo\Baz\Test {
    }';

    $f5 = function (): ?\Foo {
    };
    $e5 = 'function (): ?\Foo {
    }';

    $f6 = function (): ?Foo {
    };
    $e6 = 'function (): ?\Foo {
    }';

    $f7 = function (): ?array {
    };
    $e7 = 'function (): ?array {
    }';

    $f8 = function (): ?string {
    };
    $e8 = 'function (): ?string {
    }';

    $f9 = function (): void {
    };
    $e9 = 'function (): void {
    }';

    expect($f1)->toBeCode($e1);
    expect($f2)->toBeCode($e2);
    expect($f3)->toBeCode($e3);
    expect($f4)->toBeCode($e4);
    expect($f5)->toBeCode($e5);
    expect($f6)->toBeCode($e6);
    expect($f7)->toBeCode($e7);
    expect($f8)->toBeCode($e8);
    expect($f9)->toBeCode($e9);
});
