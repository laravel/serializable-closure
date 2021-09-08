<?php

enum GlobalEnum: string {
    case Admin = 'Administrator';
    case Guest = 'Guest';
    case Moderator = 'Moderator';
}

test('global enums', function () {

    $f = function (GlobalEnum $role) {
        return $role;
    };

    $e = 'function (\GlobalEnum $role) {
        return $role;
    }';

    expect($f)->toBeCode($e);
});

test('scoped enums', function () {

    enum ScopedEnum: string {
        case Admin = 'Administrator';
        case Guest = 'Guest';
        case Moderator = 'Moderator';
    }

    $f = function (ScopedEnum $role) {
        return $role;
    };

    $e = 'function (\ScopedEnum $role) {
        return $role;
    }';

    expect($f)->toBeCode($e);
});

test('local enums', function () {
    $f = function () {
        enum ScopedEnum: string {
            case Admin = 'Administrator';
            case Guest = 'Guest';
            case Moderator = 'Moderator';
        }

        return ScopedEnum::from('Administrator');
    };

    $e = 'function () {
        enum \ScopedEnum: string {
            case \Admin = \'Administrator\';
            case \Guest = \'Guest\';
            case \Moderator = \'Moderator\';
        }

        return \ScopedEnum::from(\'Administrator\');
    }';

    expect($f)->toBeCode($e);
});