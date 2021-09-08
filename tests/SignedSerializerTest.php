<?php
/* ===========================================================================
 * Copyright (c) 2018-2021 Zindex Software
 *
 * Licensed under the MIT License
 * =========================================================================== */

use Laravel\SerializableClosure\SerializableClosure;
use Opis\Closure\SecurityException;

test('secure closure integrity fail', function () {
    $closure = function () {
        /*x*/
    };

    SerializableClosure::setSecretKey('secret');

    $value = serialize(new SerializableClosure($closure));
    $value = str_replace('*x*', '*y*', $value);
    unserialize($value);
})->throws(SecurityException::class);

test('unsigned closure with signer', function () {
    SerializableClosure::setSecretKey(null);

    $closure = function () {
        /*x*/
    };

    $value = serialize(new SerializableClosure($closure));
    SerializableClosure::setSecretKey('secret');
    unserialize($value);
})->throws(SecurityException::class);

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
