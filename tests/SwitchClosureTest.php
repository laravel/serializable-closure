<?php

test('closure with switch', function () {
    $c = function () {
        $var = new \stdClass();

        switch(true) {
            case $var instanceof \stdClass:
                return true;

            default:
                return false;
        }
    };

    $u = s($c)();

    expect($u)->toBe(true);
})->with('serializers');
