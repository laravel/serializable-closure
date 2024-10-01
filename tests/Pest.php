<?php

use Laravel\SerializableClosure\SerializableClosure;
use Laravel\SerializableClosure\Serializers;
use Laravel\SerializableClosure\Support\ReflectionClosure;
use Laravel\SerializableClosure\UnsignedSerializableClosure;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses()->afterEach(function () {
    $_ENV['SERIALIZER'] = null;

    SerializableClosure::setSecretKey(null);
    SerializableClosure::transformUseVariablesUsing(null);
    SerializableClosure::resolveUseVariablesUsing(null);
})->in(__DIR__);

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeCode', function ($expected) {
    $code = (new ReflectionClosure($this->value))->getCode();

    expect($code)->toBe($expected);

    return $this->value;
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * Returns the given closure after serialize/unserialize.
 *
 * @param  \Closure  $closure
 * @return \Closure
 */
function s($closure, $serializer = null)
{
    switch ($serializer ?? test()->serializer) {
        case Serializers\Native::class:
            $closure = new SerializableClosure($closure);
            break;
        case Serializers\Signed::class:
            SerializableClosure::setSecretKey('secret');
            $closure = new SerializableClosure($closure);
            break;
        case UnsignedSerializableClosure::class:
            $closure = SerializableClosure::unsigned($closure);
            break;
        default:
            throw new Exception('Please use the [serializers] dataset.');
    }

    return unserialize(serialize($closure))->getClosure();
}
