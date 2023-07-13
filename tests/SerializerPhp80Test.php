<?php

use Tests\Fixtures\RegularClass;

test('named arguments', function () {
    $f1 = function (string $firstName, string $lastName) {
        return $firstName.' '.$lastName;
    };

    expect('Marco Deleu')->toBe(s($f1)(
        lastName: 'Deleu',
        firstName: 'Marco'
    ));
})->with('serializers');

test('single named argument within closures', function () {
    $f1 = function () {
        return (new SerializerPhp80NamedArguments)->publicMethod(
            namedArgument: 'string'
        );
    };

    expect('string')->toBe(s($f1)());
})->with('serializers');

test('multiple named arguments within closures', function () {
    $f1 = function () {
        return (new SerializerPhp80NamedArguments)->publicMethod(
            namedArgument: 'string', namedArgumentB: 1
        );
    };

    expect('string1')->toBe(s($f1)());
})->with('serializers');

test('multiple named arguments within nested closures', function () {
    $f1 = function () {
        $fn = fn ($namedArgument, $namedArgumentB) => (
            new SerializerPhp80NamedArguments
        )->publicMethod(namedArgument: $namedArgument, namedArgumentB: $namedArgumentB);

        return $fn(namedArgument: 'string', namedArgumentB: 1);
    };

    expect('string1')->toBe(s($f1)());
})->with('serializers');

test('named arguments with namespaced class const parameter', function () {
    $f1 = function () {
        return new RegularClass(a2: RegularClass::C);
    };

    $instance = s($f1)();

    expect($instance)->toBeInstanceOf(RegularClass::class)
        ->and($instance->a1)->toBeNull()
        ->and($instance->a2)->toBe('CONST');
})->with('serializers');

test('named arguments with namespaced class instance parameter', function () {
    $f1 = function () {
        return new RegularClass(a2: new RegularClass());
    };

    $instance = s($f1)();

    expect($instance)->toBeInstanceOf(RegularClass::class)
        ->and($instance->a1)->toBeNull()
        ->and($instance->a2)->toBeInstanceOf(RegularClass::class);
})->with('serializers');

class SerializerPhp80NamedArguments
{
    public function publicMethod(string $namedArgument, $namedArgumentB = null)
    {
        return $namedArgument.(string) $namedArgumentB;
    }
}
