<?php

use Laravel\SerializableClosure\Exceptions\PhpVersionNotSupportedException;
use Laravel\SerializableClosure\SerializableClosure;

test('serializable closure does not support PHP 7.3', function () {
    new SerializableClosure(function () {
        return 'foo';
    });
})->throws(PhpVersionNotSupportedException::class);

test('unsigned serializable closure does not support PHP 7.3', function () {
    SerializableClosure::unsigned(function () {
        return 'foo';
    });
})->throws(PhpVersionNotSupportedException::class);
