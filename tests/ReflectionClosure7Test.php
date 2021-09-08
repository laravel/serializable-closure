<?php

use Opis\Closure\ClosureContext;
use Opis\Closure\ClosureContext as SomeAlias;
use Foo\Bar as Baz;
use Foo\Baz\Qux\Forest;
use Foo\Bar;

test('instantiate non qualified class name', function () {
    $f = function () {
        new NonExisting\B;
    };
    $e = 'function () {
        new \NonExisting\B;
    }';

    expect($f)->toBeCode($e);
});

test('instantiate partially qualified namespace', function () {
    $f = function (Bar\Test $p) {
    };
    $e = 'function (\Foo\Bar\Test $p) {
    }';

    expect($f)->toBeCode($e);
});

test('fully qualified', function () {
    $f = function () {
        new \A;
    };
    $e = 'function () {
        new \A;
    }';

    expect($f)->toBeCode($e);
});

test('group namespaces', function () {
    $f = fn (): Forest => new Forest;
    $e = 'fn (): \Foo\Baz\Qux\Forest => new \Foo\Baz\Qux\Forest';

    expect($f)->toBeCode($e);
});

test('namespaced object inside closure', function () {
    $closure = function () {
        $object = new ClosureContext();

        expect($object)->toBeInstanceOf(\Opis\Closure\ClosureContext::class);
        expect($object)->toBeInstanceOf(SomeAlias::class);
    };

    $executable = s($closure);

    $executable();
})->with('serializers');
