<?php

use Laravel\SerializableClosure\Exceptions\InvalidSignatureException;
use Laravel\SerializableClosure\SerializableClosure;

test('secure closure integrity fail', function () {
    $closure = function () {
        /*x*/
    };

    SerializableClosure::setSecretKey('secret');

    $value = serialize(new SerializableClosure($closure));
    $value = str_replace('*x*', '*y*', $value);
    unserialize($value);
})->throws(InvalidSignatureException::class);

test('unsigned closure with signer', function () {
    SerializableClosure::setSecretKey(null);

    $closure = function () {
        /*x*/
    };

    $value = serialize(new SerializableClosure($closure));
    SerializableClosure::setSecretKey('secret');
    unserialize($value);
})->throws(InvalidSignatureException::class);

test('signed closure without signer', function () {
    SerializableClosure::setSecretKey('secret');

    $closure = function () {
        return true;
    };

    $value = serialize(new SerializableClosure($closure));
    SerializableClosure::setSecretKey(null);
    $closure = unserialize($value)->getClosure();
    expect($closure())->toBeTrue();
});

test('invalid signed closure without signer', function () {
    SerializableClosure::setSecretKey('secret');
    $closure = function () {
        /*x*/
    };

    $value = serialize(new SerializableClosure($closure));
    $value = str_replace('.', ',', $value);
    SerializableClosure::setSecretKey(null);
})->skip('Should this test fail?');
