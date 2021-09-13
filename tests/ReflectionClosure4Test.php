<?php

use Foo\Bar;
use Foo\{
    Bar as Baz,
};
use ReflectionClosure4Class as SomeAlias;

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

test('instantiate non qualified class name', function () {
    $f = function () {
        new NonExisting\B();
    };
    $e = 'function () {
        new \NonExisting\B();
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
        new \A();
    };
    $e = 'function () {
        new \A();
    }';

    expect($f)->toBeCode($e);
});

test('namespaced object inside closure', function () {
    $closure = function () {
        $object = new ReflectionClosure4Class();

        expect($object)->toBeInstanceOf(ReflectionClosure4Class::class);
        expect($object)->toBeInstanceOf(SomeAlias::class);
    };

    $executable = s($closure);

    $executable();
})->with('serializers');

class ReflectionClosure4Class
{
    // ..
}
