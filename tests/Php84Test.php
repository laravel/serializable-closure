<?php

it('can serialize class with virtual property', function () {
    $c = new VirtualPropWithPhp84();

    $f = function () use ($c) {
        return $c;
    };

    $s1 = s($f);

    expect('test')->toBe($s1()->test);
})->with('serializers');

class VirtualPropWithPhp84 {
    public string $test {
        get => 'test';
    }
}
