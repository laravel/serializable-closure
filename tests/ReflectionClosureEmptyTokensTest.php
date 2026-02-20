<?php

use Laravel\SerializableClosure\Support\ReflectionClosure;

/**
 * A subclass that overrides getFileTokens() to return an empty array,
 * simulating the condition where getTokens() produces [].
 */
class EmptyTokensReflectionClosure extends ReflectionClosure
{
    protected function getFileTokens(): array
    {
        return [];
    }
}

test('getTokens returns empty array when getFileTokens returns empty array', function () {
    $closure = function () {
        return 42;
    };

    $rc = new EmptyTokensReflectionClosure($closure);

    // Access the protected getTokens() via getCode(), which iterates over the tokens.
    // With no tokens the for-loop never runs, so no candidates are collected.
    // $lastItem = array_pop([]) === null → the else branch runs, setting
    // isShortClosure / isBindingRequired / isScopeRequired but leaving $code = ''.
    $code = $rc->getCode();

    expect($code)->toBe('');
});

test('getUseVariables returns empty array when getTokens returns empty array', function () {
    $x = 1;
    $closure = function () use ($x) {
        return $x;
    };

    $rc = new EmptyTokensReflectionClosure($closure);

    // getUseVariables() iterates tokens looking for T_USE; with no tokens the
    // loop body never runs, so $use stays [] and useVariables is set to [].
    $useVars = $rc->getUseVariables();

    expect($useVars)->toBe([]);
});

test('isStatic returns false when getTokens returns empty array', function () {
    $closure = function () {
    };

    $rc = new EmptyTokensReflectionClosure($closure);

    // getCode() returns '' → substr('', 0, 6) === '' which is not 'static'
    expect($rc->isStatic())->toBeFalse();
});

test('isShortClosure returns false when getTokens returns empty array', function () {
    $closure = fn () => 42;

    $rc = new EmptyTokensReflectionClosure($closure);

    // getCode() returns '' → trim('') === '' → substr('', 0, 2) !== 'fn'
    expect($rc->isShortClosure())->toBeFalse();
});
