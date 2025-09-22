<?php

use Foo\Baz\Qux\Forest;
use Some\ClassName as ClassAlias;
use Tests\Fixtures\Model;
use Tests\Fixtures\RegularClass;
use function Tests\Fixtures\{makeModel};

enum GlobalEnum {
    case Admin;
    case Guest;
    case Moderator;
}

test('named arguments', function () {
    $variable = new RegularClass();

    $f1 = function (string $a1) use ($variable) {
        return new RegularClass(
            a1: $a1,
            a2: 'string',
            a3: 1,
            a4: 1.1,
            a5: true,
            a6: null,
            a7: ['string'],
            a8: ['string', $a1, 1, null, true],
            a9: [[[[['string', $a1, 1, null, true]]]]],
            a10: new RegularClass(
                a1: $a1,
                a2: 'string',
                a3: 1,
                a4: 1.1,
                a5: true,
                a6: null,
                a7: ['string'],
                a8: ['string', $a1, 1, null, true],
                a9: [[[[['string', $a1, 1, null, true]]]]],
                a10: new RegularClass(),
                a11: RegularClass::C,
                a12: RegularClass::staticMethod(),
                a13: (new RegularClass())->instanceMethod(),
                a14: [new RegularClass(), RegularClass::C, RegularClass::staticMethod(), (new RegularClass())->instanceMethod()],
            ),
            a11: RegularClass::C,
            a12: [new RegularClass(), RegularClass::C],
            a13: RegularClass::staticMethod(),
            a14: (new RegularClass())->instanceMethod(),
            a15: fn () => new RegularClass(
                a1: $a1,
                a2: 'string',
                a3: 1,
                a4: 1.1,
                a5: true,
                a6: null,
                a7: ['string'],
                a8: ['string', $a1, 1, null, true],
                a9: [[[[['string', $a1, 1, null, true]]]]],
                a10: new RegularClass(
                    a1: $a1,
                    a2: 'string',
                    a3: 1,
                    a4: 1.1,
                    a5: true,
                    a6: null,
                    a7: ['string'],
                    a8: ['string', $a1, 1, null, true],
                    a9: [[[[['string', $a1, 1, null, true]]]]],
                    a10: new RegularClass(),
                    a11: RegularClass::C,
                    a12: RegularClass::staticMethod(),
                    a13: (new RegularClass())->instanceMethod(),
                    a14: [new RegularClass(), RegularClass::C, RegularClass::staticMethod(), (new RegularClass())->instanceMethod()],
                ),
                a11: RegularClass::C,
                a12: [new RegularClass(), RegularClass::C],
                a13: RegularClass::staticMethod(),
                a14: (new RegularClass())->instanceMethod(),
            ),
            a16: fn () => fn () => new RegularClass(
                a1: $a1,
                a2: 'string',
                a3: 1,
                a4: 1.1,
                a5: true,
                a6: null,
                a7: ['string'],
                a8: ['string', $a1, 1, null, true],
                a9: [[[[['string', $a1, 1, null, true]]]]],
                a10: new RegularClass(
                    a1: $a1,
                    a2: 'string',
                    a3: 1,
                    a4: 1.1,
                    a5: true,
                    a6: null,
                    a7: ['string'],
                    a8: ['string', $a1, 1, null, true],
                    a9: [[[[['string', $a1, 1, null, true]]]]],
                    a10: new RegularClass(),
                    a11: RegularClass::C,
                    a12: RegularClass::staticMethod(),
                    a13: (new RegularClass())->instanceMethod(),
                    a14: [new RegularClass(), RegularClass::C, RegularClass::staticMethod(), (new RegularClass())->instanceMethod()],
                ),
                a11: RegularClass::C,
                a12: [new RegularClass(), RegularClass::C],
                a13: RegularClass::staticMethod(),
                a14: (new RegularClass())->instanceMethod(),
            ),
            a17: function () use ($variable) {
                return new RegularClass(
                    a1: $a1,
                    a2: 'string',
                    a3: 1,
                    a4: 1.1,
                    a5: true,
                    a6: null,
                    a7: ['string'],
                    a8: ['string', $a1, 1, null, true],
                    a9: [[[[['string', $a1, 1, null, true]]]]],
                    a10: new RegularClass(
                        a1: $a1,
                        a2: 'string',
                        a3: 1,
                        a4: 1.1,
                        a5: true,
                        a6: null,
                        a7: ['string'],
                        a8: ['string', $a1, 1, null, true],
                        a9: [[[[['string', $a1, 1, null, true]]]]],
                        a10: new RegularClass(),
                        a11: RegularClass::C,
                        a12: RegularClass::staticMethod(),
                        a13: (new RegularClass())->instanceMethod(),
                        a14: [new RegularClass(), RegularClass::C, RegularClass::staticMethod(), (new RegularClass())->instanceMethod()],
                    ),
                    a11: RegularClass::C,
                    a12: [new RegularClass(), RegularClass::C],
                    a13: RegularClass::staticMethod(),
                    a14: (new RegularClass())->instanceMethod(),
                );
            },
            a18: reflection_closure_my_function(),
            a19: reflection_closure_my_function(ReflectionClosureGlobalEnum::Guest),
            a20: reflection_closure_my_function(enum: ReflectionClosureGlobalEnum::Guest),
            a21: match (true) {
                true => new RegularClass(),
                false => (new RegularClass()) instanceof RegularClass,
                default => reflection_closure_my_function(enum: ReflectionClosureGlobalEnum::Guest),
            },
        );
    };

    $e1 = "function (string \$a1) use (\$variable) {
        return new \Tests\Fixtures\RegularClass(
            a1: \$a1,
            a2: 'string',
            a3: 1,
            a4: 1.1,
            a5: true,
            a6: null,
            a7: ['string'],
            a8: ['string', \$a1, 1, null, true],
            a9: [[[[['string', \$a1, 1, null, true]]]]],
            a10: new \Tests\Fixtures\RegularClass(
                a1: \$a1,
                a2: 'string',
                a3: 1,
                a4: 1.1,
                a5: true,
                a6: null,
                a7: ['string'],
                a8: ['string', \$a1, 1, null, true],
                a9: [[[[['string', \$a1, 1, null, true]]]]],
                a10: new \Tests\Fixtures\RegularClass(),
                a11: \Tests\Fixtures\RegularClass::C,
                a12: \Tests\Fixtures\RegularClass::staticMethod(),
                a13: (new \Tests\Fixtures\RegularClass())->instanceMethod(),
                a14: [new \Tests\Fixtures\RegularClass(), \Tests\Fixtures\RegularClass::C, \Tests\Fixtures\RegularClass::staticMethod(), (new \Tests\Fixtures\RegularClass())->instanceMethod()],
            ),
            a11: \Tests\Fixtures\RegularClass::C,
            a12: [new \Tests\Fixtures\RegularClass(), \Tests\Fixtures\RegularClass::C],
            a13: \Tests\Fixtures\RegularClass::staticMethod(),
            a14: (new \Tests\Fixtures\RegularClass())->instanceMethod(),
            a15: fn () => new \Tests\Fixtures\RegularClass(
                a1: \$a1,
                a2: 'string',
                a3: 1,
                a4: 1.1,
                a5: true,
                a6: null,
                a7: ['string'],
                a8: ['string', \$a1, 1, null, true],
                a9: [[[[['string', \$a1, 1, null, true]]]]],
                a10: new \Tests\Fixtures\RegularClass(
                    a1: \$a1,
                    a2: 'string',
                    a3: 1,
                    a4: 1.1,
                    a5: true,
                    a6: null,
                    a7: ['string'],
                    a8: ['string', \$a1, 1, null, true],
                    a9: [[[[['string', \$a1, 1, null, true]]]]],
                    a10: new \Tests\Fixtures\RegularClass(),
                    a11: \Tests\Fixtures\RegularClass::C,
                    a12: \Tests\Fixtures\RegularClass::staticMethod(),
                    a13: (new \Tests\Fixtures\RegularClass())->instanceMethod(),
                    a14: [new \Tests\Fixtures\RegularClass(), \Tests\Fixtures\RegularClass::C, \Tests\Fixtures\RegularClass::staticMethod(), (new \Tests\Fixtures\RegularClass())->instanceMethod()],
                ),
                a11: \Tests\Fixtures\RegularClass::C,
                a12: [new \Tests\Fixtures\RegularClass(), \Tests\Fixtures\RegularClass::C],
                a13: \Tests\Fixtures\RegularClass::staticMethod(),
                a14: (new \Tests\Fixtures\RegularClass())->instanceMethod(),
            ),
            a16: fn () => fn () => new \Tests\Fixtures\RegularClass(
                a1: \$a1,
                a2: 'string',
                a3: 1,
                a4: 1.1,
                a5: true,
                a6: null,
                a7: ['string'],
                a8: ['string', \$a1, 1, null, true],
                a9: [[[[['string', \$a1, 1, null, true]]]]],
                a10: new \Tests\Fixtures\RegularClass(
                    a1: \$a1,
                    a2: 'string',
                    a3: 1,
                    a4: 1.1,
                    a5: true,
                    a6: null,
                    a7: ['string'],
                    a8: ['string', \$a1, 1, null, true],
                    a9: [[[[['string', \$a1, 1, null, true]]]]],
                    a10: new \Tests\Fixtures\RegularClass(),
                    a11: \Tests\Fixtures\RegularClass::C,
                    a12: \Tests\Fixtures\RegularClass::staticMethod(),
                    a13: (new \Tests\Fixtures\RegularClass())->instanceMethod(),
                    a14: [new \Tests\Fixtures\RegularClass(), \Tests\Fixtures\RegularClass::C, \Tests\Fixtures\RegularClass::staticMethod(), (new \Tests\Fixtures\RegularClass())->instanceMethod()],
                ),
                a11: \Tests\Fixtures\RegularClass::C,
                a12: [new \Tests\Fixtures\RegularClass(), \Tests\Fixtures\RegularClass::C],
                a13: \Tests\Fixtures\RegularClass::staticMethod(),
                a14: (new \Tests\Fixtures\RegularClass())->instanceMethod(),
            ),
            a17: function () use (\$variable) {
                return new \Tests\Fixtures\RegularClass(
                    a1: \$a1,
                    a2: 'string',
                    a3: 1,
                    a4: 1.1,
                    a5: true,
                    a6: null,
                    a7: ['string'],
                    a8: ['string', \$a1, 1, null, true],
                    a9: [[[[['string', \$a1, 1, null, true]]]]],
                    a10: new \Tests\Fixtures\RegularClass(
                        a1: \$a1,
                        a2: 'string',
                        a3: 1,
                        a4: 1.1,
                        a5: true,
                        a6: null,
                        a7: ['string'],
                        a8: ['string', \$a1, 1, null, true],
                        a9: [[[[['string', \$a1, 1, null, true]]]]],
                        a10: new \Tests\Fixtures\RegularClass(),
                        a11: \Tests\Fixtures\RegularClass::C,
                        a12: \Tests\Fixtures\RegularClass::staticMethod(),
                        a13: (new \Tests\Fixtures\RegularClass())->instanceMethod(),
                        a14: [new \Tests\Fixtures\RegularClass(), \Tests\Fixtures\RegularClass::C, \Tests\Fixtures\RegularClass::staticMethod(), (new \Tests\Fixtures\RegularClass())->instanceMethod()],
                    ),
                    a11: \Tests\Fixtures\RegularClass::C,
                    a12: [new \Tests\Fixtures\RegularClass(), \Tests\Fixtures\RegularClass::C],
                    a13: \Tests\Fixtures\RegularClass::staticMethod(),
                    a14: (new \Tests\Fixtures\RegularClass())->instanceMethod(),
                );
            },
            a18: \\reflection_closure_my_function(),
            a19: \\reflection_closure_my_function(\ReflectionClosureGlobalEnum::Guest),
            a20: \\reflection_closure_my_function(enum: \ReflectionClosureGlobalEnum::Guest),
            a21: match (true) {
                true => new \Tests\Fixtures\RegularClass(),
                false => (new \Tests\Fixtures\RegularClass()) instanceof \Tests\Fixtures\RegularClass,
                default => \\reflection_closure_my_function(enum: \ReflectionClosureGlobalEnum::Guest),
            },
        );
    }";

    expect($f1)->toBeCode($e1);
})->with('serializers');

test('enums', function () {
    $f = function (GlobalEnum $role) {
        return $role;
    };

    $e = 'function (\GlobalEnum $role) {
        return $role;
    }';

    expect($f)->toBeCode($e);

    enum ScopedEnum {
        case Admin;
        case Guest;
        case Moderator;
    }

    $f = function (ScopedEnum $role) {
        return $role;
    };

    $e = 'function (\ScopedEnum $role) {
        return $role;
    }';

    expect($f)->toBeCode($e);
});


enum GlobalBackedEnum: string {
    case Admin = 'Administrator';
    case Guest = 'Guest';
    case Moderator = 'Moderator';
}

test('backed enums', function () {

    $f = function (GlobalBackedEnum $role) {
        return $role;
    };

    $e = 'function (\GlobalBackedEnum $role) {
        return $role;
    }';

    expect($f)->toBeCode($e);

    enum ScopedBackedEnum: string {
        case Admin = 'Administrator';
        case Guest = 'Guest';
        case Moderator = 'Moderator';
    }

    $f = function (ScopedBackedEnum $role) {
        return $role;
    };

    $e = 'function (\ScopedBackedEnum $role) {
        return $role;
    }';

    expect($f)->toBeCode($e);
});

test('array unpacking', function () {
    $f = function () {
        $array1 = ['a' => 1];

        $array2 = ['b' => 2];

        return ['a' => 0, ...$array1, ...$array2];
    };

    $e = "function () {
        \$array1 = ['a' => 1];

        \$array2 = ['b' => 2];

        return ['a' => 0, ...\$array1, ...\$array2];
    }";

    expect($f)->toBeCode($e);
});

test('new in initializers', function () {
    $f = function () {
        return new ReflectionClosurePhp81Controller();
    };

    $e = 'function () {
        return new \ReflectionClosurePhp81Controller();
    }';

    expect($f)->toBeCode($e);
});

test('readonly properties', function () {
    $f = function () {
        $controller = new ReflectionClosurePhp81Controller();

        $controller->service = 'foo';
    };

    $e = 'function () {
        $controller = new \ReflectionClosurePhp81Controller();

        $controller->service = \'foo\';
    }';

    expect($f)->toBeCode($e);
});

test('first-class callable with closures', function () {
    $f = function ($a) {
        $f = fn ($b) => $a + $b + 1;

        return $f(...);
    };

    $e = 'function ($a) {
        $f = fn ($b) => $a + $b + 1;

        return $f(...);
    }';

    expect($f)->toBeCode($e);
});

test('first-class callable with methods', function () {
    $f = (new ReflectionClosurePhp81Controller())->publicGetter(...);

    $e = 'function ()
    {
        return $this->privateGetter();
    }';

    expect($f)->toBeCode($e);

    $f = (new ReflectionClosurePhp81Controller())->publicGetterResolver(...);

    $e = 'function ()
    {
        return $this->privateGetterResolver(...);
    }';

    expect($f)->toBeCode($e);
});

test('first-class callable with static methods', function () {
    $f = ReflectionClosurePhp81Controller::publicStaticGetter(...);

    $e = 'static function ()
    {
        return static::privateStaticGetter();
    }';

    expect($f)->toBeCode($e);

    $f = ReflectionClosurePhp81Controller::publicStaticGetterResolver(...);

    $e = 'static function ()
    {
        return static::privateStaticGetterResolver(...);
    }';

    expect($f)->toBeCode($e);
});

test('first-class callable final method', function () {
    $f = (new ReflectionClosurePhp81Controller())->finalPublicGetterResolver(...);

    $e = 'function ()
    {
        return $this->privateGetterResolver(...);
    }';

    expect($f)->toBeCode($e);

    $f = ReflectionClosurePhp81Controller::finalPublicStaticGetterResolver(...);

    $e = 'static function ()
    {
        return static::privateStaticGetterResolver(...);
    }';

    expect($f)->toBeCode($e);
});

test('first-class callable self return type', function () {
    $f = (new ReflectionClosurePhp81Controller())->getSelf(...);

    $e = 'function (self $instance): self
    {
        return $instance;
    }';

    expect($f)->toBeCode($e);
});

test('intersection types', function () {
    $f = function (ClassAlias&Forest $service): ClassAlias&Forest {
        return $service;
    };

    $e = 'function (\Some\ClassName&\Foo\Baz\Qux\Forest $service): \Some\ClassName&\Foo\Baz\Qux\Forest {
        return $service;
    }';

    expect($f)->toBeCode($e);
});

test('never type', function () {
    $f = function (): never {
        throw new RuntimeException('Something wrong happened.');
    };

    $e = 'function (): never {
        throw new \RuntimeException(\'Something wrong happened.\');
    }';

    expect($f)->toBeCode($e);
});

test('array_is_list', function () {
    $f = function () {
        return array_is_list([]);
    };

    $e = 'function () {
        return \array_is_list([]);
    }';

    expect($f)->toBeCode($e);
});

test('final class constants', function () {
    $f = function () {
        return ReflectionClosurePhp81Service::X;
    };

    $e = 'function () {
    return ReflectionClosurePhp81Service::X;
};';

    expect($f)->toBeCode($e);
})->skip('Constants in anonymous classes is not supported.');

test('first-class callable namespaces', function () {
    $model = new Model();

    $f = $model->make(...);

    $e = 'function (\Tests\Fixtures\Model $model): \Tests\Fixtures\Model
    {
        return new \Tests\Fixtures\Model();
    }';

    expect($f)->toBeCode($e);
});

test('static first-class callable namespaces', function () {
    $model = new Model();

    $f = $model->staticMake(...);

    $e = 'static function (\Tests\Fixtures\Model $model): \Tests\Fixtures\Model
    {
        return new \Tests\Fixtures\Model();
    }';

    expect($f)->toBeCode($e);
});

test('function attributes without arguments', function () {
    $model = new Model();

    $f = #[MyAttribute] function () {
        return true;
    };

    $e = <<<EOF
#[MyAttribute()]
function () {
        return true;
    }
EOF;

    expect($f)->toBeCode($e);
});

test('function attributes with arguments', function () {
    $model = new Model();

    $f = #[MyAttribute('My " \' Argument 1', Model::class)] function () {
        return true;
    };

    $e = <<<'EOF'
#[MyAttribute('My " \' Argument 1', 'Tests\\Fixtures\\Model')]
function () {
        return true;
    }
EOF;

    expect($f)->toBeCode($e);
});

test('function attributes with named arguments', function () {
    $model = new Model();

    $f = #[MyAttribute(string: 'My " \' Argument 1', model:Model::class)] function () {
        return false;
    };

    $e = <<<'EOF'
#[MyAttribute(string: 'My " \' Argument 1', model: 'Tests\\Fixtures\\Model')]
function () {
        return false;
    }
EOF;

    expect($f)->toBeCode($e);
});

test('function attributes with first-class callable with methods', function () {
    $model = new Model();

    $f = (new SerializerPhp81Controller())->publicGetter(...);

    $e = <<<'EOF'
#[Tests\Fixtures\ModelAttribute()]
#[MyAttribute('My " \' Argument 1', 'Tests\\Fixtures\\Model')]
function ()
    {
        return $this->privateGetter();
    }
EOF;

    expect($f)->toBeCode($e);
});

test('function attributes with array arguments', function () {
    $model = new Model();

    $f = #[MyAttribute('My Argument', ['one', 'two'])] function (): bool {
        return true;
    };

    $e = <<<'EOF'
#[MyAttribute('My Argument', array (
  0 => 'one',
  1 => 'two',
))]
function (): bool {
        return true;
    }
EOF;

    expect($f)->toBeCode($e);
});

class ReflectionClosurePhp81Service
{
}

class ReflectionClosurePhp81Controller
{
    public function __construct(
        public readonly ReflectionClosurePhp81Service $service = new ReflectionClosurePhp81Service(),
    ) {
        // ..
    }

    public function publicGetter()
    {
        return $this->privateGetter();
    }

    private function privateGetter()
    {
        return $this->service;
    }

    public static function publicStaticGetter()
    {
        return static::privateStaticGetter();
    }

    public static function privateStaticGetter()
    {
        return (new ReflectionClosurePhp81Controller())->service;
    }

    public function publicGetterResolver()
    {
        return $this->privateGetterResolver(...);
    }

    private function privateGetterResolver()
    {
        return fn () => $this->service;
    }

    public static function publicStaticGetterResolver()
    {
        return static::privateStaticGetterResolver(...);
    }

    public static function privateStaticGetterResolver()
    {
        return fn () => (new ReflectionClosurePhp81Controller())->service;
    }

    final public function finalPublicGetterResolver()
    {
        return $this->privateGetterResolver(...);
    }

    final public static function finalPublicStaticGetterResolver()
    {
        return static::privateStaticGetterResolver(...);
    }

    public function getSelf(self $instance): self
    {
        return $instance;
    }
}

enum ReflectionClosureGlobalEnum {
    case Admin;
    case Guest;
    case Moderator;
}

function reflection_closure_my_function(SerializerGlobalEnum $enum = SerializerGlobalEnum::Admin)
{
    return $enum;
}
