<?php

use Foo\Bar as Baz;
use Foo\Baz\Qux;
use Foo\Baz\Qux\Forest;
use Laravel\SerializableClosure\Support\ReflectionClosure;
use Tests\Fixtures\Model;
use Tests\Fixtures\RegularClass;

test('is short closure', function () {
    $f1 = fn () => 1;
    $f2 = static fn () => 1;
    $f3 = function () {
        fn () => 1;
    };

    expect((new ReflectionClosure($f1))->isShortClosure())->toBeTrue();
    expect((new ReflectionClosure($f2))->isShortClosure())->toBeTrue();
    expect((new ReflectionClosure($f3))->isShortClosure())->toBeFalse();
});

test('basic short closure', function () {
    $f1 = fn () => 'hello';
    $e1 = 'fn () => \'hello\'';

    $f2 = fn &() => 'hello';
    $e2 = 'fn &() => \'hello\'';

    $f3 = fn ($a) => 'hello';
    $e3 = 'fn ($a) => \'hello\'';

    $f4 = fn (&$a) => 'hello';
    $e4 = 'fn (&$a) => \'hello\'';

    $f5 = fn (&$a): string => 'hello';
    $e5 = 'fn (&$a): string => \'hello\'';

    expect($f1)->toBeCode($e1);
    expect($f2)->toBeCode($e2);
    expect($f3)->toBeCode($e3);
    expect($f4)->toBeCode($e4);
    expect($f5)->toBeCode($e5);
});

test('resolve types', function () {
    $f1 = fn (Baz $a) => 'hello';
    $e1 = 'fn (\Foo\Bar $a) => \'hello\'';

    $f2 = fn (Baz $a): Qux => 'hello';
    $e2 = 'fn (\Foo\Bar $a): \Foo\Baz\Qux => \'hello\'';

    $f3 = fn (Baz $a): int => (function (Qux $x) {
    })();
    $e3 = 'fn (\Foo\Bar $a): int => (function (\Foo\Baz\Qux $x) {
    })()';

    $f4 = fn () => new Qux();
    $e4 = 'fn () => new \Foo\Baz\Qux()';

    $f5 = fn () => new class extends Baz\Qux {};
    $e5 = 'fn () => new class extends \Foo\Bar\Qux {}';

    $f6 = fn () => new class extends Baz\Qux implements Baz\Qux {};
    $e6 = 'fn () => new class extends \Foo\Bar\Qux implements \Foo\Bar\Qux {}';

    $f7 = fn () => new class implements Baz\Qux, Baz\Qux {};
    $e7 = 'fn () => new class implements \Foo\Bar\Qux, \Foo\Bar\Qux {}';

    $f8 = function () {
        $a = new class implements Baz\Qux, Baz\Qux {};

        $b = new class implements Baz\Qux {};
    };

    $e8 = 'function () {
        $a = new class implements \Foo\Bar\Qux, \Foo\Bar\Qux {};

        $b = new class implements \Foo\Bar\Qux {};
    }';

    $f9 = function () {
        $a = new class implements Baz\Qux, Baz\Qux {};

        $b = new class extends Forest implements Baz\Qux
        {
            public Baz\Qux $qux;

            public function foo()
            {
                return new class {};
            }

            public function qux(Baz\Qux $qux): Baz\Qux
            {
                return static fn () => new class extends Forest implements Baz\Qux {
                    //
                };
            }
        };
    };

    $e9 = 'function () {
        $a = new class implements \Foo\Bar\Qux, \Foo\Bar\Qux {};

        $b = new class extends \Foo\Baz\Qux\Forest implements \Foo\Bar\Qux
        {
            public \Foo\Bar\Qux $qux;

            public function foo()
            {
                return new class {};
            }

            public function qux(\Foo\Bar\Qux $qux): \Foo\Bar\Qux
            {
                return static fn () => new class extends \Foo\Baz\Qux\Forest implements \Foo\Bar\Qux {
                    //
                };
            }
        };
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

test('class keywords instantiation', function () {
    test()->assertEquals(
        'function () {
            return new self();
        }',
        c(function () {
            return new self();
        })
    );

    test()->assertEquals(
        'function () {
            return new static();
        }',
        c(function () {
            return new static();
        })
    );

    test()->assertEquals(
        'function () {
            return new parent();
        }',
        c(function () {
            return new parent();
        })
    );
});

test('function inside expressions and arrays', function () {
    $f1 = (fn () => 1);
    $e1 = 'fn () => 1';

    $f2 = [fn () => 1];
    $e2 = 'fn () => 1';

    $f3 = [fn () => 1, 0];
    $e3 = 'fn () => 1';

    $f4 = fn () => ($a === true) && (! empty([0, 1]));
    $e4 = 'fn () => ($a === true) && (! empty([0, 1]))';

    expect($f1)->toBeCode($e1);
    expect($f2[0])->toBeCode($e2);
    expect($f3[0])->toBeCode($e3);
    expect($f4)->toBeCode($e4);
});

test('serialize', function ($e) {
    $f1 = fn () => 'hello';
    $c1 = s($f1);

    $f2 = fn ($a, $b) => $a + $b;
    $c2 = s($f2);

    $a = 4;
    $f3 = fn (int $b, int $c = 5): int => ($a + $b) * $c;
    $c3 = s($f3);

    expect($c1())->toEqual('hello');
    expect($c2(4, 3))->toEqual(7);
    expect($c3(4))->toEqual(40);
    expect($c3(4, 6))->toEqual(48);
})->with('serializers');

test('typed properties', function () {
    $user = new User();
    $s = s(function () {
        return true;
    });
    expect($s())->toBeTrue();

    $user = new User();
    $product = new Product();
    $product->name = 'PC';
    $user->setProduct($product);

    $u = s(function () use ($user) {
        return $user->getProduct()->name;
    });

    expect($u())->toEqual('PC');
})->with('serializers');

test('group namespaces', function () {
    $f = fn (): Forest => new Forest();
    $e = 'fn (): \Foo\Baz\Qux\Forest => new \Foo\Baz\Qux\Forest()';

    expect($f)->toBeCode($e);
});

test('from callable namespaces', function () {
    $f = Closure::fromCallable([new Model, 'make']);

    $e = 'function (\Tests\Fixtures\Model $model): \Tests\Fixtures\Model
    {
        return new \Tests\Fixtures\Model();
    }';

    expect($f)->toBeCode($e);
});

test('from static callable namespaces', function () {
    $f = Closure::fromCallable([Model::class, 'staticMake']);

    $e = 'static function (\Tests\Fixtures\Model $model): \Tests\Fixtures\Model
    {
        return new \Tests\Fixtures\Model();
    }';

    expect($f)->toBeCode($e);
});

test('ternanry operator new without constructor', function () {
    $f = function () {
        $flag = true;

        return $flag ? new RegularClass : new RegularClass;
    };
    $e = 'function () {
        $flag = true;

        return $flag ? new \Tests\Fixtures\RegularClass : new \Tests\Fixtures\RegularClass;
    }';

    expect($f)->toBeCode($e);
});

// Helpers
function c(Closure $closure)
{
    $r = new ReflectionClosure($closure);

    return $r->getCode();
}

class Product
{
    public string $name;
}

class User
{
    protected Product $product;

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }
}
