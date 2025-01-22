<?php

use Foo\Bar as Baz;
use Foo\Baz\Qux;
use Foo\Baz\Qux\Forest;
use Laravel\SerializableClosure\Support\ReflectionClosure;
use Tests\Fixtures\Model;
use Tests\Fixtures\RegularClass;

test('resolve instanceof with class_exists()', function () {
	$f1 = fn (Baz $a) => $this instanceof (class_exists(Foo::class) ? Foo::class : Forest::class);
	$e1 = 'fn (\Foo\Bar $a) => $this instanceof (class_exists(\Foo:class) ? \Foo::class : \Foo\Baz\Qux\Forest::class)';
	
    expect($f1)->toBeCode($e1);
});