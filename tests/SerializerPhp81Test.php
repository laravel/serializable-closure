<?php

enum SerializerGlobalEnum: string {
    case Admin = 'Administrator';
    case Guest = 'Guest';
    case Moderator = 'Moderator';
}

test('enums', function () {
    $f = function (SerializerGlobalEnum $role) {
        return $role;
    };

    $f = s($f);

    expect($f(SerializerGlobalEnum::Guest))->toBe(
        SerializerGlobalEnum::Guest
    );

    if (! enum_exists(SerializerScopedEnum::class)) {
        enum SerializerScopedEnum: string {
            case Admin = 'Administrator';
            case Guest = 'Guest';
            case Moderator = 'Moderator';
        }
    }

    $f = function () {
        return SerializerScopedEnum::Admin;
    };

    $f = s($f);

    expect($f()->value)->toBe('Administrator');
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

test('first-class callable', function () {
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
}

