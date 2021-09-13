<?php

enum GlobalEnum: string {
    case Admin = 'Administrator';
    case Guest = 'Guest';
    case Moderator = 'Moderator';
}

test('enums', function () {

    $f = function (GlobalEnum $role) {
        return $role;
    };

    $e = 'function (\GlobalEnum $role) {
        return $role;
    }';

    expect($f)->toBeCode($e);

    enum ScopedEnum: string {
        case Admin = 'Administrator';
        case Guest = 'Guest';
        case Moderator = 'Moderator';
    }

    $f = function (ScopedEnum $role) {
        return $role;
    };

    $e = 'function (\ScopedEnum $role) {
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
        $controller = new SerializerPhp81Controller();

        $controller->service = 'foo';
    };

    $e = 'function () {
        $controller = new \SerializerPhp81Controller();

        $controller->service = \'foo\';
    }';

    expect($f)->toBeCode($e);
});

test('first-class callable', function () {
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

test('intersection types', function () {
    $f = function (ReflectionClosurePhp81HasId&ReflectionClosurePhp81HasName $service): ReflectionClosureHasId&ReflectionClosurePhp81HasName {
        return $service;
    };

    $e = 'function (\ReflectionClosurePhp81HasId&\ReflectionClosurePhp81HasName $service): \ReflectionClosureHasId&\ReflectionClosurePhp81HasName {
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

interface ReflectionClosurePhp81HasId {}
interface ReflectionClosurePhp81HasName {}

class ReflectionClosurePhp81Service implements ReflectionClosurePhp81HasId, ReflectionClosurePhp81HasName
{
}

class ReflectionClosurePhp81Controller
{
    public function __construct(
        public readonly ReflectionClosurePhp81Service $service = new ReflectionClosurePhp81Service(),
    ) {
        // ..
    }
}

