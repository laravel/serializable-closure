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

function serializer_php_80_switch_statement_test_is_two($a)
{
    return $a === 2;
}

class SerializerPhp80SwitchStatementClass
{
    public static function isThree($a)
    {
        return $a === 3;
    }

    public function isFour($a)
    {
        return $a === 4;
    }
}

class SerializerPhp80Class
{
}

test('instanceof', function () {
    $closure = function (object $a): array {
        $b = $a instanceof DateTime || $a instanceof SerializerPhp80Class;

        return [
            $b,
            $a instanceof DateTime || $a instanceof SerializerPhp80Class,
            (function (object $a): bool {
                return ($a instanceof DateTime || $a instanceof SerializerPhp80Class) === true;
            })(a: $a),
        ];
    };

    $u = s($closure);

    expect($u(new DateTime))->toEqual([true, true, true])
        ->and($u(new SerializerPhp80Class))->toEqual([true, true, true])
        ->and($u(new stdClass))->toEqual([false, false, false]);
})->with('serializers');

test('switch statement', function () {
    $closure = function ($a) {
        switch (true) {
            case $a === 1:
                return 'one';
            case serializer_php_74_switch_statement_test_is_two(a: $a):
                return 'two';
            case SerializerPhp80SwitchStatementClass::isThree(a: $a):
                return 'three';
            case (new SerializerPhp80SwitchStatementClass)->isFour(a: $a):
                return 'four';
            case $a instanceof SerializerPhp80SwitchStatementClass:
                return 'five';
            case $a instanceof DateTime:
                return 'six';
            default:
                return 'other';
        }
    };

    $u = s($closure);

    expect($u(1))->toEqual('one')
        ->and($u(2))->toEqual('two')
        ->and($u(3))->toEqual('three')
        ->and($u(4))->toEqual('four')
        ->and($u(new SerializerPhp80SwitchStatementClass))->toEqual('five')
        ->and($u(new DateTime))->toEqual('six')
        ->and($u(999))->toEqual('other');
})->with('serializers');

function match_statement_test_is_two($a)
{
    return $a === 2;
}

class MatchStatementClass
{
    public static function isThree($a)
    {
        return $a === 3;
    }

    public function isFour($a)
    {
        return $a === 4;
    }
}

test('match statement', function () {
    $closure = function ($a) {
        return match (true) {
            $a === 1 => 'one',
            match_statement_test_is_two($a) => 'two',
            MatchStatementClass::isThree($a) => 'three',
            (new MatchStatementClass)->isFour(a: $a) => 'four',
            $a instanceof MatchStatementClass => 'five',
            $a instanceof DateTime => 'six',
            default => 'other',
        };
    };

    $u = s($closure);

    expect($u(1))->toEqual('one')
        ->and($u(2))->toEqual('two')
        ->and($u(3))->toEqual('three')
        ->and($u(4))->toEqual('four')
        ->and($u(new MatchStatementClass))->toEqual('five')
        ->and($u(new DateTime))->toEqual('six')
        ->and($u(999))->toEqual('other');
})->with('serializers');
