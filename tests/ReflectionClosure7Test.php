<?php

enum Role: string {
    case Admin = 'Administrator';
    case Guest = 'Guest';
    case Moderator = 'Moderator';
}

test('enums', function () {

    $f = function (Role $role) {
        return $role;
    };

    $e = 'function (\Role $role) {
        return $role;
    }';

    expect($f)->toBeCode($e);
})->with('serializers');