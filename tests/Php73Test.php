<?php

use Laravel\SerializableClosure\Exceptions\PhpVersionNotSupportedException;
use Laravel\SerializableClosure\SerializableClosure;

it('does not support PHP 7.3', function () {
    new SerializableClosure(function () {
        return 'foo';
    });
})->throws(PhpVersionNotSupportedException::class);
