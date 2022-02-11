<?php

use Tests\Fixtures\Model;

enum SerializerGlobalEnum {
    case Admin;
    case Guest;
    case Moderator;
}

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

enum SerializerGlobalBackedEnum: string {
    case Admin = 'Administrator';
    case Guest = 'Guest';
    case Moderator = 'Moderator';
}

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

interface SerializerPhp81HasId {}
interface SerializerPhp81HasName {}

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

