<?php

test('named arguments', function () {
    $f1 = function (string $firstName, string $lastName) {
        return $firstName.' '.$lastName;
    };

    expect('Marco Deleu')->toBe(s($f1)(
        lastName: 'Deleu',
        firstName: 'Marco'
    ));
})->with('serializers');

test('named arguments within closures', function () {
    $f1 = function () {
        return (new NamedArguments)->publicMethod(namedArgument: 'string');
    };

    expect('string')->toBe(s($f1)());
})->with('serializers');

class SerializerPhp80NamedArguments
{
    public function publicMethod(string $namedArgument)
    {
        return $namedArgument;
    }
}
