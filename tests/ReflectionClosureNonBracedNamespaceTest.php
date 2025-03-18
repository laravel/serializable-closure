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
