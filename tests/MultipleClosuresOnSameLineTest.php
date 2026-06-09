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

test('closure using variable named static is not detected as static closure', function () {
    $static = 'not a static closure';
    $c1 = fn () => $static;
    $c2 = fn () => 'other'; // @phpstan-ignore-line

    $s1 = new SerializableClosure($c1);
    $s2 = new SerializableClosure($c2);

    expect($s1->getClosure()())->toBe('not a static closure');
    expect($s2->getClosure()())->toBe('other');

    $u1 = unserialize(serialize($s1))->getClosure();
    $u2 = unserialize(serialize($s2))->getClosure();

    expect($u1())->toBe('not a static closure');
    expect($u2())->toBe('other');
});

test('multiple traditional closures on same line', function () {
    $c1 = function () {
        return 1;
    };
    $c2 = function () {
        return 2;
    }; // @phpstan-ignore-line

    $s1 = new SerializableClosure($c1);
    $s2 = new SerializableClosure($c2);

    expect($s1->getClosure()())->toBe(1);
    expect($s2->getClosure()())->toBe(2);

    $u1 = unserialize(serialize($s1))->getClosure();
    $u2 = unserialize(serialize($s2))->getClosure();

    expect($u1())->toBe(1);
    expect($u2())->toBe(2);
});

test('multiple closures on same line with identical signatures', function () {
    $closures = [fn () => 'a', fn () => 'b', fn () => 'c'];

    $serialized = serialize(array_map(fn ($c) => new SerializableClosure($c), $closures));
    $unserialized = array_map(fn ($sc) => $sc->getClosure(), unserialize($serialized));

    expect($unserialized[0]())->toBe('a');
    expect($unserialized[1]())->toBe('b');
    expect($unserialized[2]())->toBe('c');
});

test('closure with identical signature keeps its candidate on repeated serialization', function () {
    $closures = [fn () => 'x', fn () => 'y'];

    $s1 = new SerializableClosure($closures[0]);
    $s2 = new SerializableClosure($closures[1]);

    expect(unserialize(serialize($s1))->getClosure()())->toBe('x');
    expect(unserialize(serialize($s2))->getClosure()())->toBe('y');

    // Serializing the same instances again must yield the same results.
    expect(unserialize(serialize($s1))->getClosure()())->toBe('x');
    expect(unserialize(serialize($s2))->getClosure()())->toBe('y');
});

test('multiple traditional closures with identical signatures on same line', function () {
    $c1 = function () { return 1; }; $c2 = function () { return 2; }; // @phpstan-ignore-line

    $u1 = unserialize(serialize(new SerializableClosure($c1)))->getClosure();
    $u2 = unserialize(serialize(new SerializableClosure($c2)))->getClosure();

    expect($u1())->toBe(1);
    expect($u2())->toBe(2);
});

test('identical signature closures created in a loop', function () {
    $all = [];

    for ($i = 0; $i < 2; $i++) {
        $pair = [fn () => 'first', fn () => 'second'];

        $all[] = unserialize(serialize(new SerializableClosure($pair[0])))->getClosure();
        $all[] = unserialize(serialize(new SerializableClosure($pair[1])))->getClosure();
    }

    expect($all[0]())->toBe('first');
    expect($all[1]())->toBe('second');
    expect($all[2]())->toBe('first');
    expect($all[3]())->toBe('second');
});

test('multiple traditional closures with use clause on same line', function () {
    $a = 1;
    $b = 2;
    $c1 = function () use ($a) {
        return $a;
    };
    $c2 = function () use ($b) {
        return $b;
    }; // @phpstan-ignore-line

    $s1 = new SerializableClosure($c1);
    $s2 = new SerializableClosure($c2);

    expect($s1->getClosure()())->toBe(1);
    expect($s2->getClosure()())->toBe(2);

    $u1 = unserialize(serialize($s1))->getClosure();
    $u2 = unserialize(serialize($s2))->getClosure();

    expect($u1())->toBe(1);
    expect($u2())->toBe(2);
});
