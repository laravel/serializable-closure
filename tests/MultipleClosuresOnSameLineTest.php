<?php

use Laravel\SerializableClosure\SerializableClosure;

test('multiple closures on same line with different arguments', function () {
    $c1 = fn ($a) => $a;
    $c2 = fn ($b) => $b; // @phpstan-ignore-line

    $s1 = new SerializableClosure($c1);
    $s2 = new SerializableClosure($c2);

    expect($s1->getClosure()(1))->toBe(1);
    expect($s2->getClosure()(2))->toBe(2);

    $u1 = unserialize(serialize($s1))->getClosure();
    $u2 = unserialize(serialize($s2))->getClosure();

    expect($u1(1))->toBe(1);
    expect($u2(2))->toBe(2);
});

test('multiple closures on same line with different static variables', function () {
    $a = 1;
    $b = 2;
    $c1 = fn () => $a;
    $c2 = fn () => $b; // @phpstan-ignore-line

    $s1 = new SerializableClosure($c1);
    $s2 = new SerializableClosure($c2);

    expect($s1->getClosure()())->toBe(1);
    expect($s2->getClosure()())->toBe(2);

    $u1 = unserialize(serialize($s1))->getClosure();
    $u2 = unserialize(serialize($s2))->getClosure();

    expect($u1())->toBe(1);
    expect($u2())->toBe(2);
});

test('mixture of static and non-static closures', function () {
    $c1 = fn () => 1;
    $c2 = static fn () => 2; // @phpstan-ignore-line

    $s1 = new SerializableClosure($c1);
    $s2 = new SerializableClosure($c2);

    expect($s1->getClosure()())->toBe(1);
    expect($s2->getClosure()())->toBe(2);

    $u1 = unserialize(serialize($s1))->getClosure();
    $u2 = unserialize(serialize($s2))->getClosure();

    expect($u1())->toBe(1);
    expect($u2())->toBe(2);
});
