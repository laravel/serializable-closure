<?php

// Fake
use Foo\Bar;
use Foo\Baz as Qux;
use Tests\Fixtures\RegularClass;

test('new instance', function () {
    $f = function () {
        $c = '\A';
        new $c();
    };
    $e = 'function () {
        $c = \'\A\';
        new $c();
    }';
    expect($f)->toBeCode($e);
});

test('new instance2', function () {
    $f = function () {
        new A();
    };
    $e = 'function () {
        new \A();
    }';
    expect($f)->toBeCode($e);

    $f = function () {
        new A\B();
    };
    $e = 'function () {
        new \A\B();
    }';
    expect($f)->toBeCode($e);

    $f = function () {
        new \A();
    };
    $e = 'function () {
        new \A();
    }';
    expect($f)->toBeCode($e);

    $f = function () {
        new A(new B(), [new C()]);
    };
    $e = 'function () {
        new \A(new \B(), [new \C()]);
    }';
    expect($f)->toBeCode($e);

    $f = function () {
        new self();
        new static();
        new parent();
    };
    $e = 'function () {
        new self();
        new static();
        new parent();
    }';
    expect($f)->toBeCode($e);
});

test('instance of', function () {
    $f = function () {
        $c = null;
        $b = '\X\y';
        v($c instanceof $b);
    };
    $e = 'function () {
        $c = null;
        $b = \'\X\y\';
        v($c instanceof $b);
    }';
    expect($f)->toBeCode($e);
});

test('closure resolve arguments', function () {
    $f1 = function (Bar $p) {
    };
    $e1 = 'function (\Foo\Bar $p) {
    }';

    $f2 = function (Bar\Test $p) {
    };
    $e2 = 'function (\Foo\Bar\Test $p) {
    }';

    $f3 = function (Qux $p) {
    };
    $e3 = 'function (\Foo\Baz $p) {
    }';

    $f4 = function (Qux\Test $p) {
    };
    $e4 = 'function (\Foo\Baz\Test $p) {
    }';

    $f5 = function (\Foo $p) {
    };
    $e5 = 'function (\Foo $p) {
    }';

    $f6 = function (Foo $p) {
    };
    $e6 = 'function (\Foo $p) {
    }';

    expect($f1)->toBeCode($e1);
    expect($f2)->toBeCode($e2);
    expect($f3)->toBeCode($e3);
    expect($f4)->toBeCode($e4);
    expect($f5)->toBeCode($e5);
    expect($f6)->toBeCode($e6);
});

test('cloure resolve in body', function () {
    $f1 = function () {
        return new Bar();
    };
    $e1 = 'function () {
        return new \Foo\Bar();
    }';

    $f2 = function () {
        return new Bar\Test();
    };
    $e2 = 'function () {
        return new \Foo\Bar\Test();
    }';

    $f3 = function () {
        return new Qux();
    };
    $e3 = 'function () {
        return new \Foo\Baz();
    }';

    $f4 = function () {
        return new Qux\Test();
    };
    $e4 = 'function () {
        return new \Foo\Baz\Test();
    }';

    $f5 = function () {
        return new \Foo();
    };
    $e5 = 'function () {
        return new \Foo();
    }';

    $f6 = function () {
        return new Foo();
    };
    $e6 = 'function () {
        return new \Foo();
    }';

    expect($f1)->toBeCode($e1);
    expect($f2)->toBeCode($e2);
    expect($f3)->toBeCode($e3);
    expect($f4)->toBeCode($e4);
    expect($f5)->toBeCode($e5);
    expect($f6)->toBeCode($e6);
});

test('closure resolve static method', function () {
    $f1 = function () {
        return Bar::test();
    };
    $e1 = 'function () {
        return \Foo\Bar::test();
    }';

    $f2 = function () {
        return Bar\Test::test();
    };
    $e2 = 'function () {
        return \Foo\Bar\Test::test();
    }';

    $f3 = function () {
        return Qux::test();
    };
    $e3 = 'function () {
        return \Foo\Baz::test();
    }';

    $f4 = function () {
        return Qux\Test::test();
    };
    $e4 = 'function () {
        return \Foo\Baz\Test::test();
    }';

    $f5 = function () {
        return Foo::test();
    };
    $e5 = 'function () {
        return \Foo::test();
    }';

    $f6 = function () {
        return Foo::test();
    };
    $e6 = 'function () {
        return \Foo::test();
    }';

    expect($f1)->toBeCode($e1);
    expect($f2)->toBeCode($e2);
    expect($f3)->toBeCode($e3);
    expect($f4)->toBeCode($e4);
    expect($f5)->toBeCode($e5);
    expect($f6)->toBeCode($e6);
});

test('static inside closure', function () {
    $f1 = function () {
        return static::foo();
    };
    $e1 = 'function () {
        return static::foo();
    }';

    $f2 = function ($a) {
        return $a instanceof static;
    };
    $e2 = 'function ($a) {
        return $a instanceof static;
    }';

    expect($f1)->toBeCode($e1);
    expect($f2)->toBeCode($e2);
});

test('self inside closure', function () {
    $f1 = function () {
        return self::foo();
    };
    $e1 = 'function () {
        return self::foo();
    }';

    $f2 = function ($a) {
        return $a instanceof self;
    };
    $e2 = 'function ($a) {
        return $a instanceof self;
    }';

    expect($f1)->toBeCode($e1);
    expect($f2)->toBeCode($e2);
});

test('parent inside closure', function () {
    $f1 = function () {
        return parent::foo();
    };
    $e1 = 'function () {
        return parent::foo();
    }';

    $f2 = function ($a) {
        return $a instanceof parent;
    };
    $e2 = 'function ($a) {
        return $a instanceof parent;
    }';

    expect($f1)->toBeCode($e1);
    expect($f2)->toBeCode($e2);
});

test('interpolation1', function () {
    $f1 = function () {
        return "{$foo}{$bar}{$foobar}";
    };
    $e1 = 'function () {
        return "{$foo}{$bar}{$foobar}";
    }';

    expect($f1)->toBeCode($e1);
});

test('consts', function () {
    $f1 = function () {
        return RegularClass::C;
    };

    $e1 = 'function () {
        return \Tests\Fixtures\RegularClass::C;
    }';

    expect($f1)->toBeCode($e1);
});

function reflection_closure_php_74_switch_statement_test_is_two($a)
{
    return $a === 2;
}

class ReflectionClosurePhp74InstanceOfTest
{
}

class ReflectionClosurePhp74SwitchStatementTest
{
}

test('instanceof', function () {
    $f1 = function ($a) {
        $b = $a instanceof DateTime || $a instanceof ReflectionClosurePhp74InstanceOfTest || $a instanceof RegularClass;

        return [
            $b,
            $a instanceof DateTime || $a instanceof ReflectionClosurePhp74InstanceOfTest || $a instanceof RegularClass,
            (function ($a) {
                return ($a instanceof DateTime || $a instanceof ReflectionClosurePhp74InstanceOfTest || $a instanceof RegularClass) === true;
            })($a),
        ];
    };

    $e1 = 'function ($a) {
        $b = $a instanceof \DateTime || $a instanceof \ReflectionClosurePhp74InstanceOfTest || $a instanceof \Tests\Fixtures\RegularClass;

        return [
            $b,
            $a instanceof \DateTime || $a instanceof \ReflectionClosurePhp74InstanceOfTest || $a instanceof \Tests\Fixtures\RegularClass,
            (function ($a) {
                return ($a instanceof \DateTime || $a instanceof \ReflectionClosurePhp74InstanceOfTest || $a instanceof \Tests\Fixtures\RegularClass) === true;
            })($a),
        ];
    }';

    expect($f1)->toBeCode($e1);
});

test('switch statement', function () {
    $f1 = function ($a) {
        switch (true) {
            case $a === 1:
                return 'one';
            case reflection_closure_php_74_switch_statement_test_is_two($a):
                return 'two';
            case ReflectionClosurePhp74SwitchStatementTest::isThree($a):
                return 'three';
            case (new ReflectionClosurePhp74SwitchStatementTest)->isFour($a):
                return 'four';
            case $a instanceof ReflectionClosurePhp74SwitchStatementTest:
                return 'five';
            case $a instanceof DateTime:
                return 'six';
            case $a instanceof RegularClass:
                return 'seven';
            default:
                return 'other';
        }
    };

    $e1 = 'function ($a) {
        switch (true) {
            case $a === 1:
                return \'one\';
            case \reflection_closure_php_74_switch_statement_test_is_two($a):
                return \'two\';
            case \ReflectionClosurePhp74SwitchStatementTest::isThree($a):
                return \'three\';
            case (new \ReflectionClosurePhp74SwitchStatementTest)->isFour($a):
                return \'four\';
            case $a instanceof \ReflectionClosurePhp74SwitchStatementTest:
                return \'five\';
            case $a instanceof \DateTime:
                return \'six\';
            case $a instanceof \Tests\Fixtures\RegularClass:
                return \'seven\';
            default:
                return \'other\';
        }
    }';

    expect($f1)->toBeCode($e1);
});
