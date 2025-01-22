<?php

use Foo\Bar as Baz;
use Foo\Baz\Qux;
use Foo\Baz\Qux\Forest;
use Laravel\SerializableClosure\Support\ReflectionClosure;
use Tests\Fixtures\Model;
use Tests\Fixtures\RegularClass;

test('resolve instanceof with expression', function () {
	$f1 = fn (Baz $a) => $this instanceof (Forest::class); // Turning `instanceof` into an expression results into this incorrect compilation
	$e1 = 'fn (\Foo\Bar $a) => $this instanceof (\Foo\Baz\Qux\Forest::class)';
	
	$f2 = fn (Baz $a) => $this instanceof (class_exists(Foo::class) ? Foo::class : Forest::class);
	$e2 = 'fn (\Foo\Bar $a) => $this instanceof (class_exists(\Foo:class) ? \Foo::class : \Foo\Baz\Qux\Forest::class)';
	
    expect($f1)->toBeCode($e1);
    expect($f2)->toBeCode($e2);
});