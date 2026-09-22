<?php

use Laravel\SerializableClosure\SerializableClosure;

dataset('unsigned closures by php version', [
    '8.2' => ['O:55:"Laravel\SerializableClosure\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\SerializableClosure\Serializers\Native":5:{s:3:"use";a:1:{s:1:"a";i:100;}s:8:"function";s:43:"function () use ($a) {
    return $a * 2;
}";s:5:"scope";N;s:4:"this";N;s:4:"self";s:32:"00000000000000050000000000000000";}}'],
    '8.3' => ['O:55:"Laravel\SerializableClosure\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\SerializableClosure\Serializers\Native":5:{s:3:"use";a:1:{s:1:"a";i:100;}s:8:"function";s:43:"function () use ($a) {
    return $a * 2;
}";s:5:"scope";N;s:4:"this";N;s:4:"self";s:32:"00000000000000050000000000000000";}}'],
    '8.4' => ['O:55:"Laravel\SerializableClosure\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\SerializableClosure\Serializers\Native":5:{s:3:"use";a:1:{s:1:"a";i:100;}s:8:"function";s:43:"function () use ($a) {
    return $a * 2;
}";s:5:"scope";N;s:4:"this";N;s:4:"self";s:32:"00000000000000050000000000000000";}}'],
    '8.5' => ['O:55:"Laravel\SerializableClosure\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\SerializableClosure\Serializers\Native":5:{s:3:"use";a:1:{s:1:"a";i:100;}s:8:"function";s:43:"function () use ($a) {
    return $a * 2;
}";s:5:"scope";N;s:4:"this";N;s:4:"self";s:32:"00000000000000050000000000000000";}}'],
    '8.6' => ['O:55:"Laravel\SerializableClosure\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\SerializableClosure\Serializers\Native":5:{s:3:"use";a:1:{s:1:"a";i:100;}s:8:"function";s:43:"function () use ($a) {
    return $a * 2;
}";s:5:"scope";N;s:4:"this";N;s:4:"self";i:4;}}'],
]);

dataset('signed closures by php version', [
    '8.2' => ['O:47:"Laravel\SerializableClosure\SerializableClosure":1:{s:12:"serializable";O:46:"Laravel\SerializableClosure\Serializers\Signed":2:{s:12:"serializable";s:232:"O:46:"Laravel\SerializableClosure\Serializers\Native":5:{s:3:"use";a:1:{s:1:"a";i:100;}s:8:"function";s:43:"function () use ($a) {
    return $a * 2;
}";s:5:"scope";N;s:4:"this";N;s:4:"self";s:32:"00000000000000080000000000000000";}";s:4:"hash";s:44:"r+6isEBszyAe8J1z3aB/TS3clMssbBGtik1AJw0Kz8c=";}}'],
    '8.3' => ['O:47:"Laravel\SerializableClosure\SerializableClosure":1:{s:12:"serializable";O:46:"Laravel\SerializableClosure\Serializers\Signed":2:{s:12:"serializable";s:232:"O:46:"Laravel\SerializableClosure\Serializers\Native":5:{s:3:"use";a:1:{s:1:"a";i:100;}s:8:"function";s:43:"function () use ($a) {
    return $a * 2;
}";s:5:"scope";N;s:4:"this";N;s:4:"self";s:32:"00000000000000080000000000000000";}";s:4:"hash";s:44:"r+6isEBszyAe8J1z3aB/TS3clMssbBGtik1AJw0Kz8c=";}}'],
    '8.4' => ['O:47:"Laravel\SerializableClosure\SerializableClosure":1:{s:12:"serializable";O:46:"Laravel\SerializableClosure\Serializers\Signed":2:{s:12:"serializable";s:232:"O:46:"Laravel\SerializableClosure\Serializers\Native":5:{s:3:"use";a:1:{s:1:"a";i:100;}s:8:"function";s:43:"function () use ($a) {
    return $a * 2;
}";s:5:"scope";N;s:4:"this";N;s:4:"self";s:32:"00000000000000080000000000000000";}";s:4:"hash";s:44:"r+6isEBszyAe8J1z3aB/TS3clMssbBGtik1AJw0Kz8c=";}}'],
    '8.5' => ['O:47:"Laravel\SerializableClosure\SerializableClosure":1:{s:12:"serializable";O:46:"Laravel\SerializableClosure\Serializers\Signed":2:{s:12:"serializable";s:232:"O:46:"Laravel\SerializableClosure\Serializers\Native":5:{s:3:"use";a:1:{s:1:"a";i:100;}s:8:"function";s:43:"function () use ($a) {
    return $a * 2;
}";s:5:"scope";N;s:4:"this";N;s:4:"self";s:32:"00000000000000080000000000000000";}";s:4:"hash";s:44:"r+6isEBszyAe8J1z3aB/TS3clMssbBGtik1AJw0Kz8c=";}}'],
    '8.6' => ['O:47:"Laravel\SerializableClosure\SerializableClosure":1:{s:12:"serializable";O:46:"Laravel\SerializableClosure\Serializers\Signed":2:{s:12:"serializable";s:196:"O:46:"Laravel\SerializableClosure\Serializers\Native":5:{s:3:"use";a:1:{s:1:"a";i:100;}s:8:"function";s:43:"function () use ($a) {
    return $a * 2;
}";s:5:"scope";N;s:4:"this";N;s:4:"self";i:8;}";s:4:"hash";s:44:"KPZ/lEJE6uHxVGhv5DK1kcGcvRmBI2uaX1gO/rN21tg=";}}'],
]);

dataset('self-referencing closures by php version', [
    '8.2' => ['O:55:"Laravel\SerializableClosure\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\SerializableClosure\Serializers\Native":5:{s:3:"use";a:1:{s:1:"a";O:49:"Laravel\SerializableClosure\Support\SelfReference":1:{s:4:"hash";s:32:"00000000000000050000000000000000";}}s:8:"function";s:40:"function () use (&$a) {
    return $a;
}";s:5:"scope";N;s:4:"this";N;s:4:"self";s:32:"00000000000000050000000000000000";}}'],
    '8.3' => ['O:55:"Laravel\SerializableClosure\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\SerializableClosure\Serializers\Native":5:{s:3:"use";a:1:{s:1:"a";O:49:"Laravel\SerializableClosure\Support\SelfReference":1:{s:4:"hash";s:32:"00000000000000050000000000000000";}}s:8:"function";s:40:"function () use (&$a) {
    return $a;
}";s:5:"scope";N;s:4:"this";N;s:4:"self";s:32:"00000000000000050000000000000000";}}'],
    '8.4' => ['O:55:"Laravel\SerializableClosure\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\SerializableClosure\Serializers\Native":5:{s:3:"use";a:1:{s:1:"a";O:49:"Laravel\SerializableClosure\Support\SelfReference":1:{s:4:"hash";s:32:"00000000000000050000000000000000";}}s:8:"function";s:40:"function () use (&$a) {
    return $a;
}";s:5:"scope";N;s:4:"this";N;s:4:"self";s:32:"00000000000000050000000000000000";}}'],
    '8.5' => ['O:55:"Laravel\SerializableClosure\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\SerializableClosure\Serializers\Native":5:{s:3:"use";a:1:{s:1:"a";O:49:"Laravel\SerializableClosure\Support\SelfReference":1:{s:4:"hash";s:32:"00000000000000050000000000000000";}}s:8:"function";s:40:"function () use (&$a) {
    return $a;
}";s:5:"scope";N;s:4:"this";N;s:4:"self";s:32:"00000000000000050000000000000000";}}'],
    '8.6' => ['O:55:"Laravel\SerializableClosure\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\SerializableClosure\Serializers\Native":5:{s:3:"use";a:1:{s:1:"a";O:49:"Laravel\SerializableClosure\Support\SelfReference":1:{s:4:"hash";i:4;}}s:8:"function";s:40:"function () use (&$a) {
    return $a;
}";s:5:"scope";N;s:4:"this";N;s:4:"self";i:4;}}'],
]);

test('an unsigned closure serialized on a given PHP version can be unserialized and invoked', function (string $serialized) {
    $closure = unserialize($serialized)->getClosure();

    expect($closure())->toBe(200);
})->with('unsigned closures by php version');

test('a signed closure serialized on a given PHP version can be unserialized and invoked', function (string $serialized) {
    SerializableClosure::setSecretKey('secret');

    $closure = unserialize($serialized)->getClosure();

    expect($closure())->toBe(200);
})->with('signed closures by php version');

test('a self-referencing closure serialized on a given PHP version can be unserialized and invoked', function (string $serialized) {
    $closure = unserialize($serialized)->getClosure();

    expect($closure())->toBe($closure);
})->with('self-referencing closures by php version');
