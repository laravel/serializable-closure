<?php

use Illuminate\Support\Carbon;
use Tests\Fixtures\Model;
use Tests\Fixtures\ModelAttribute;
use Tests\Fixtures\RegularClass;

test('enums', function () {
    $f = function (SerializerGlobalEnum $role) {
        return $role;
    };

    $f = s($f);

    expect($f(SerializerGlobalEnum::Guest))->toBe(
        SerializerGlobalEnum::Guest
    );

    $role = SerializerGlobalEnum::Admin;

    $f = function () use ($role) {
        return $role;
    };

    $f = s($f);

    expect($f())->toBe(SerializerGlobalEnum::Admin);

    if (! enum_exists(SerializerScopedEnum::class)) {
        enum SerializerScopedEnum {
            case Admin;
            case Guest;
            case Moderator;
        }
    }

    $f = function () {
        return SerializerScopedEnum::Admin;
    };

    $f = s($f);

    expect($f()->name)->toBe('Admin');

    $role = SerializerScopedEnum::Admin;

    $f = function () use ($role) {
        return $role;
    };

    $f = s($f);

    expect($f())->toBe(SerializerScopedEnum::Admin);
})->with('serializers');

test('enums properties', function () {
    $object = new ClassWithEnumProperty();
    $f = $object->getClosure();

    $f = s($f);

    expect($f())
        ->name->toBe('Admin')
        ->value->toBeNull();
})->with('serializers');

test('backed enums', function () {
    $f = function (SerializerGlobalBackedEnum $role) {
        return $role;
    };

    $f = s($f);

    expect($f(SerializerGlobalBackedEnum::Guest))->toBe(
        SerializerGlobalBackedEnum::Guest
    );

    $role = SerializerGlobalBackedEnum::Admin;

    $f = function () use ($role) {
        return $role;
    };

    $f = s($f);

    expect($f())->toBe(SerializerGlobalBackedEnum::Admin);

    if (! enum_exists(SerializerScopedBackedEnum::class)) {
        enum SerializerScopedBackedEnum: string {
            case Admin = 'Administrator';
            case Guest = 'Guest';
            case Moderator = 'Moderator';
        }
    }

    $f = function () {
        return SerializerScopedBackedEnum::Admin;
    };

    $f = s($f);

    expect($f())->name->toBe('Admin')
        ->value->toBe('Administrator');

    $role = SerializerScopedBackedEnum::Admin;

    $f = function () use ($role) {
        return $role;
    };

    $f = s($f);

    expect($f())->toBe(SerializerScopedBackedEnum::Admin);
})->with('serializers');

test('backed enums properties', function () {
    $object = new ClassWithBackedEnumProperty();
    $f = $object->getClosure();

    $f = s($f);

    expect($f())
        ->name->toBe('Admin')
        ->value->toBe('Administrator');
})->with('serializers');

test('array unpacking', function () {
    $f = function () {
        $array1 = ['a' => 1];

        $array2 = ['b' => 2];

        return ['a' => 0, ...$array1, ...$array2];
    };

    $f = s($f);

    expect($f())->toBe([
        'a' => 1,
        'b' => 2,
    ]);
})->with('serializers');

test('new in initializers', function () {
    $f = function () {
        return new SerializerPhp81Controller();
    };

    $f = s($f);

    expect($f()->service)->toBeInstanceOf(
        SerializerPhp81Service::class,
    );
})->with('serializers');

test('readonly properties', function () {
    $f = function () {
        $controller = new SerializerPhp81Controller();

        $controller->service = 'foo';
    };

    $f = s($f);

    expect($f)->toThrow(function (Error $e) {
        expect($e->getMessage())->toBe(
            'Cannot modify readonly property SerializerPhp81Controller::$service',
        );
    });
})->with('serializers');

test('readonly properties from parent scope variable', function () {
    $controller = new SerializerPhp81Controller();

    $f = static function () use ($controller) {
        return $controller;
    };

    $f = s($f);

    expect($f()->service)->toBeInstanceOf(
        SerializerPhp81Service::class,
    );
})->with('serializers');

test('readonly properties declared in parent', function () {
    $childWithDefaultValue = new SerializerPhp81Child();

    $f = static function () use ($childWithDefaultValue) {
        return $childWithDefaultValue;
    };

    $f = s($f);

    expect($f()->property)->toBe(1);

    $child = new SerializerPhp81Child(100);

    $f = static function () use ($child) {
        return $child;
    };

    $f = s($f);

    expect($f()->property)->toBe(100);
})->with('serializers');

test('first-class callable with closures', function () {
    $f = function ($value) {
        return $value;
    };

    $f = s($f);

    expect($f(...)('foo'))->toBe('foo');

    $f = function ($a) {
        $f = fn ($b) => $a + $b + 1;

        return $f(...);
    };

    $f = s($f);

    expect($f(1)(2))->toBe(4);
})->with('serializers');

test('first-class callable with methods', function () {
    $f = (new SerializerPhp81Controller())->publicGetter(...);

    $f = s($f);

    expect($f())->toBeInstanceOf(SerializerPhp81Service::class);

    $f = (new SerializerPhp81Controller())->publicGetterResolver(...);

    $f = s($f);

    expect($f()()())->toBeInstanceOf(SerializerPhp81Service::class);
})->with('serializers');

test('first-class callable with static methods', function () {
    $f = SerializerPhp81Controller::publicStaticGetter(...);

    $f = s($f);

    expect($f())->toBeInstanceOf(SerializerPhp81Service::class);

    $f = SerializerPhp81Controller::publicStaticGetterResolver(...);

    $f = s($f);

    expect($f()()())->toBeInstanceOf(SerializerPhp81Service::class);
})->with('serializers');

test('first-class callable final method', function () {
    $f = (new SerializerPhp81Controller())->finalPublicGetterResolver(...);

    $f = s($f);

    expect($f()()())->toBeInstanceOf(SerializerPhp81Service::class);

    $f = SerializerPhp81Controller::finalPublicStaticGetterResolver(...);

    $f = s($f);

    expect($f()()())->toBeInstanceOf(SerializerPhp81Service::class);
})->with('serializers');

test('first-class callable self return type', function () {
    $f = (new SerializerPhp81Controller())->getSelf(...);

    $f = s($f);

    $controller = new SerializerPhp81Controller();

    expect($f($controller))->toBeInstanceOf(SerializerPhp81Controller::class);
})->with('serializers');

test('intersection types', function () {
    $f = function (SerializerPhp81HasName&SerializerPhp81HasId $service): SerializerPhp81HasName&SerializerPhp81HasId {
        return $service;
    };

    $f = s($f);

    expect($f(new SerializerPhp81Service))->toBeInstanceOf(
        SerializerPhp81Service::class,
    );
})->with('serializers');

test('never type', function () {
    $f = function (): never {
        throw new RuntimeException('Something wrong happened.');
    };

    $f = s($f);

    expect($f)->toThrow(RuntimeException::class);
})->with('serializers');

test('array_is_list', function () {
    $f = function () {
        return array_is_list([]);
    };

    $f = s($f);

    expect($f())->toBeTrue();
})->with('serializers');

test('final class constants', function () {
    $f = function () {
        return SerializerPhp81Service::X;
    };

    $f = s($f);

    expect($f())->toBe('foo');
})->with('serializers');

test('constructor property promotion', function () {
    $class = new PropertyPromotion('public', 'protected', 'private');

    $f1 = fn () => $class;

    $object = s($f1)();

    expect($object->public)->toBe('public');
    expect($object->getProtected())->toBe('protected');
    expect($object->getPrivate())->toBe('private');
})->with('serializers');

test('first-class callable namespaces', function () {
    $model = new Model();

    $f = $model->make(...);

    $f = s($f);

    expect($f(new Model))->toBeInstanceOf(Model::class);
})->with('serializers');

test('static first-class callable namespaces', function () {
    $model = new Model();

    $f = $model->staticMake(...);

    $f = s($f);

    expect($f(new Model))->toBeInstanceOf(Model::class);
})->with('serializers');

test('function attributes without arguments', function () {
    $model = new Model();

    $f = #[MyAttribute] function () {
        return true;
    };

    $f = s($f);

    $reflector = new ReflectionFunction($f);

    expect($reflector->getAttributes())->sequence(
        fn ($attribute) => $attribute
            ->getName()->toBe(MyAttribute::class)
            ->getArguments()->toBeEmpty(),
    );

    expect($f())->toBeTrue();
})->with('serializers');

test('function attributes with arguments', function () {
    $model = new Model();

    $f = #[MyAttribute('My " \' Argument 1', Model::class)] function () {
        return false;
    };

    $f = s($f);

    $reflector = new ReflectionFunction($f);

    expect($reflector->getAttributes())->sequence(
        fn ($attribute) => $attribute
            ->getName()->toBe(MyAttribute::class)
            ->getArguments()->toBe([
                'My " \' Argument 1', Model::class,
            ])
    );

    expect($f())->toBeFalse();
})->with('serializers');

test('named arguments', function () {
    $variable = 'variableValue';

    $f = function (string $a1) use ($variable) {
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
            a18: serializer_my_function(),
            a19: serializer_my_function(SerializerGlobalEnum::Guest),
            a20: serializer_my_function(enum: SerializerGlobalEnum::Guest),
        );
    };

    $f = s($f);

    $instance = $f('a1');

    $expectedArray = [
        'a1' => 'a1',
        'a2' => 'string',
        'a3' => 1,
        'a4' => 1.1,
        'a5' => true,
        'a6' => null,
        'a7' => ['string'],
        'a8' => ['string', 'a1', 1, null, true],
        'a9' => [[[[['string', 'a1', 1, null, true]]]]],
    ];

    expect($instance)
        ->toMatchArray($expectedArray)
        ->toMatchArray([
            'a18' => SerializerGlobalEnum::Admin,
            'a19' => SerializerGlobalEnum::Guest,
            'a20' => SerializerGlobalEnum::Guest,
        ])
        ->and($instance->a15->__invoke())->toMatchArray($expectedArray);
})->with('serializers');

test('function attributes with named arguments', function () {
    $model = new Model();

    $f = #[MyAttribute(string: 'My " \' Argument 1', model:Model::class)] function () {
        return false;
    };

    $f = s($f);

    $reflector = new ReflectionFunction($f);

    expect($reflector->getAttributes())->sequence(function ($attribute) {

        $attribute
            ->getName()->toBe(MyAttribute::class)
            ->getArguments()->toBe([
                'string' => 'My " \' Argument 1',
                'model' => Model::class,
            ]);

        expect($attribute->value->newInstance())
            ->string->toBe('My " \' Argument 1')
            ->model->toBe(Model::class);
    });

    expect($f())->toBeFalse();
})->with('serializers');

test('function attributes with first-class callable with methods', function () {
    $f = (new SerializerPhp81Controller())->publicGetter(...);

    $f = s($f);

    $reflector = new ReflectionFunction($f);

    expect($reflector->getAttributes())->sequence(
        fn ($attribute) => $attribute
            ->getName()->toBe(ModelAttribute::class)
            ->getArguments()->toBe([]),
        fn ($attribute) => $attribute
            ->getName()->toBe(MyAttribute::class)
            ->getArguments()->toBe([
                'My " \' Argument 1', Model::class,
            ])
    );

    expect($f())->toBeInstanceOf(SerializerPhp81Service::class);
})->with('serializers');

interface SerializerPhp81HasId {}
interface SerializerPhp81HasName {}

class SerializerPhp81Child extends SerializerPhp81Parent {}

class SerializerPhp81Parent
{
    public function __construct(
        public readonly int $property = 1,
    ) {}
}

class SerializerPhp81Service implements SerializerPhp81HasId, SerializerPhp81HasName
{
    final public const X = 'foo';
}

class SerializerPhp81Controller
{
    public function __construct(
        public readonly SerializerPhp81Service $service = new SerializerPhp81Service(),
    ) {
        // ..
    }

    #[ModelAttribute]
    #[MyAttribute('My " \' Argument 1', Model::class)]
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
        return (new SerializerPhp81Controller())->service;
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
        return fn () => (new SerializerPhp81Controller())->service;
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

enum SerializerGlobalEnum {
    case Admin;
    case Guest;
    case Moderator;
}

enum SerializerGlobalBackedEnum: string {
    case Admin = 'Administrator';
    case Guest = 'Guest';
    case Moderator = 'Moderator';
}

#[Attribute(Attribute::TARGET_METHOD|Attribute::TARGET_FUNCTION)]
class MyAttribute
{
    public function __construct(public $string, public $model)
    {
        // ..
    }
}

class ClassWithEnumProperty
{
    public SerializerGlobalEnum $enum = SerializerGlobalEnum::Admin;

    public function getClosure()
    {
        return function () {
            return $this->enum;
        };
    }
}

test('named arguments with namespaced enum parameter', function () {
    $f1 = function () {
        return new RegularClass(a2: RegularClass::C);
    };

    expect(s($f1)())->toBeInstanceOf(RegularClass::class);
})->with('serializers');

test('carbon serialization', function () {
    $now = Carbon::createFromDate(2011, 1, 1);

    Carbon::setTestNow($now);

    $startDate = Carbon::now();

    $f1 = fn () => $startDate;

    expect(s($f1)())
        ->toBeInstanceOf(Carbon::class)
        ->format('Y-m-d')->toBe('2011-01-01');
})->with('serializers');

class ClassWithBackedEnumProperty
{
    public SerializerGlobalBackedEnum $enum = SerializerGlobalBackedEnum::Admin;

    public function getClosure()
    {
        return function () {
            return $this->enum;
        };
    }
}

function serializer_my_function(SerializerGlobalEnum $enum = SerializerGlobalEnum::Admin)
{
    return $enum;
}
