<?php

namespace Space;

test('relative namespace (non-braced)', function () {
    $f1 = fn (Foo $foo): Foo => new Foo();
    $e1 = 'fn (\Space\Foo $foo): \Space\Foo => new \Space\Foo()';

    $f2 = fn (Foo\Bar $fooBar): Foo\Bar => new Foo\Bar();
    $e2 = 'fn (\Space\Foo\Bar $fooBar): \Space\Foo\Bar => new \Space\Foo\Bar()';

    expect($f1)->toBeCode($e1);
    expect($f2)->toBeCode($e2);
});

namespace Irrelevant;
namespace Sub\Space;

test('relative other namespace (non-braced)', function () {
    $f1 = fn (Foo $foo): Foo => new Foo();
    $e1 = 'fn (\Sub\Space\Foo $foo): \Sub\Space\Foo => new \Sub\Space\Foo()';

    expect($f1)->toBeCode($e1);
});

namespace Irrelevant;

// Shouldn't be used below, as not in the same namespace
use Wrong as Qux;

namespace Space;

test('not using use from other namespace', function () {
    $f1 = fn (Qux $qux) => true;
    $e1 = 'fn (\Space\Qux $qux) => true';

    expect($f1)->toBeCode($e1);
});

// Shouldn't be used above, as declared after usage.  Not currently supported though.
// use AlsoWrong as Qux;
