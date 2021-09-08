<?php

use Laravel\SerializableClosure\Support\ReflectionClosure;

// Fake
use Some\ClassName as ClassAlias;

test('union types', function () {
    $f1 = fn (): string|int|false|Bar|null => 1;
    $e1 = 'fn (): string|int|false|\Bar|null => 1';

    $f2 = fn (): \Foo|\Bar => 1;
    $e2 = 'fn (): \Foo|\Bar => 1';

    $f3 = fn (): int|false => false;
    $e3 = 'fn (): int|false => false';

    $f4 = function (): null|MyClass|ClassAlias|Relative\Ns\ClassName|\Absolute\Ns\ClassName {
        return null;
    };
    $e4 = 'function (): null|\MyClass|\Some\ClassName|\Relative\Ns\ClassName|\Absolute\Ns\ClassName {
        return null;
    }';

    expect($f1)->toBeCode($e1);
    expect($f2)->toBeCode($e2);
    expect($f3)->toBeCode($e3);
    expect($f4)->toBeCode($e4);
});

test('mixed type', function () {
    $f1 = function (): mixed {
        return 42;
    };
    $e1 = 'function (): mixed {
        return 42;
    }';

    expect($f1)->toBeCode($e1);
});

test('null safe operator', function () {
    $f1 = function () {
        $obj = new \stdClass();
        return $obj?->invalid();
    };
    $e1 = 'function () {
        $obj = new \stdClass();
        return $obj?->invalid();
    }';

    expect($f1)->toBeCode($e1);
});

test('trailling comma', function () {
    $f1 = function (string $param, ) {
    };
    $e1 = 'function (string $param, ) {
    }';

    expect($f1)->toBeCode($e1);
});

test('named arguments', function () {
    $f1 = function (string $firstName, string $lastName) {
        return $firstName . ' ' . $lastName;
    };

    expect('Marco Deleu')->toBe(s($f1)(
        lastName: 'Deleu',
        firstName: 'Marco'
    ));
})->with('serializers');

test('constructor property promotion', function () {
    $class = new PropertyPromotion('public', 'protected', 'private');

    $f1 = fn () => $class;

    $object = s($f1)();

    expect($object->public)->toBe('public');
    expect($object->getProtected())->toBe('protected');
    expect($object->getPrivate())->toBe('private');
})->with('serializers');

class PropertyPromotion
{
    public function __construct(
        public string    $public,
        protected string $protected,
        private string   $private,
    ) {
    }

    public function getProtected(): string
    {
        return $this->protected;
    }

    public function getPrivate(): string
    {
        return $this->private;
    }
}
