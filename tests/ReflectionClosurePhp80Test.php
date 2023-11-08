<?php

// Fake
use Some\ClassName as ClassAlias;
use Tests\Fixtures\RegularClass;

test('union types', function () {
    $f1 = fn (): string|int|false|Bar|null => 1;
    $e1 = 'fn (): string|int|false|\Bar|null => 1';

    $f2 = fn (): \Foo|\Bar => 1;
    $e2 = 'fn (): \Foo|\Bar => 1';

    $f3 = fn (): int|false => false;
    $e3 = 'fn (): int|false => false';

    $f4 = function (): null|MyClass|ClassAlias|Relative\Ns\ClassName|\Absolute\Ns\ClassName {
        return null;
    };
    $e4 = 'function (): null|\MyClass|\Some\ClassName|\Relative\Ns\ClassName|\Absolute\Ns\ClassName {
        return null;
    }';

    expect($f1)->toBeCode($e1);
    expect($f2)->toBeCode($e2);
    expect($f3)->toBeCode($e3);
    expect($f4)->toBeCode($e4);
});

test('mixed type', function () {
    $f1 = function (): mixed {
        return 42;
    };
    $e1 = 'function (): mixed {
        return 42;
    }';

    expect($f1)->toBeCode($e1);
});

test('null safe operator with methods', function () {
    $f1 = function () {
        $obj = new \stdClass();

        return $obj?->invalid();
    };
    $e1 = 'function () {
        $obj = new \stdClass();

        return $obj?->invalid();
    }';

    expect($f1)->toBeCode($e1);
});

test('null safe operator with properties', function () {
    $f1 = function () {
        $obj = new \stdClass();

        return $obj?->invalid;
    };
    $e1 = 'function () {
        $obj = new \stdClass();

        return $obj?->invalid;
    }';

    expect($f1)->toBeCode($e1);
});

test('trailing comma', function () {
    $f1 = function (string $param, ) {
    };
    $e1 = 'function (string $param, ) {
    }';

    expect($f1)->toBeCode($e1);
});

test('named arguments', function () {
    $f1 = function (string $firstName, string $lastName) {
        return $firstName.' '.$lastName;
    };

    $e1 = "function (string \$firstName, string \$lastName) {
        return \$firstName.' '.\$lastName;
    }";

    expect($f1)->toBeCode($e1);
});

test('single named argument within closures', function () {
    $f1 = function () {
        return (new ReflectionClosurePhp80NamedArguments)->publicMethod(namedArgument: 'string');
    };

    $e1 = "function () {
        return (new \ReflectionClosurePhp80NamedArguments)->publicMethod(namedArgument: 'string');
    }";

    expect($f1)->toBeCode($e1);
});

test('multiple named arguments within closures', function () {
    $f1 = function () {
        return (new ReflectionClosurePhp80NamedArguments)->publicMethod(namedArgument: 'string', namedArgumentB: 1);
    };

    $e1 = "function () {
        return (new \ReflectionClosurePhp80NamedArguments)->publicMethod(namedArgument: 'string', namedArgumentB: 1);
    }";

    expect($f1)->toBeCode($e1);
});

test('named arguments with switch cases and instanceof', function () {
    $f1 = function ($a) {
        switch (true) {
            case (new RegularClass(a2: $a))->a2 instanceof RegularClass:
                return (new RegularClass(a2: $a))->a2;
            default:
                return new RegularClass(a2: RegularClass::C);
        }
    };

    $e1 = 'function ($a) {
        switch (true) {
            case (new \Tests\Fixtures\RegularClass(a2: $a))->a2 instanceof \Tests\Fixtures\RegularClass:
                return (new \Tests\Fixtures\RegularClass(a2: $a))->a2;
            default:
                return new \Tests\Fixtures\RegularClass(a2: \Tests\Fixtures\RegularClass::C);
        }
    }';

    expect($f1)->toBeCode($e1);
});

test('multiple named arguments within nested closures', function () {
    $f1 = function () {
        $fn = fn ($namedArgument, $namedArgumentB) => (
            new ReflectionClosurePhp80NamedArguments
        )->publicMethod(namedArgument: $namedArgument, namedArgumentB: $namedArgumentB);

        return $fn(namedArgument: 'string', namedArgumentB: 1);
    };

    $e1 = "function () {
        \$fn = fn (\$namedArgument, \$namedArgumentB) => (
            new \ReflectionClosurePhp80NamedArguments
        )->publicMethod(namedArgument: \$namedArgument, namedArgumentB: \$namedArgumentB);

        return \$fn(namedArgument: 'string', namedArgumentB: 1);
    }";

    expect($f1)->toBeCode($e1);
})->with('serializers');

class ReflectionClosurePhp80NamedArguments
{
    public function publicMethod(string $namedArgument, $namedArgumentB = null)
    {
        return $namedArgument.(string) $namedArgumentB;
    }
}

class PropertyPromotion
{
    public function __construct(
        public string $public,
        protected string $protected,
        private string $private,
    ) {
    }

    public function getProtected(): string
    {
        return $this->protected;
    }

    public function getPrivate(): string
    {
        return $this->private;
    }
}

function reflection_closure_php_80_switch_statement_test_is_two($a)
{
    return $a === 2;
}

class ReflectionClosurePhp80InstanceOfTest
{
};

class ReflectionClosurePhp80SwitchStatementTest
{
}

test('instanceof', function () {
    $f1 = function (object $a): array {
        $b = $a instanceof DateTime || $a instanceof ReflectionClosurePhp80InstanceOfTest || $a instanceof RegularClass;

        return [
            $b,
            ($a instanceof DateTime || $a instanceof ReflectionClosurePhp80InstanceOfTest || $a instanceof RegularClass),
            (function (object $a): bool {
                return ($a instanceof DateTime || $a instanceof ReflectionClosurePhp80InstanceOfTest || $a instanceof RegularClass) === true;
            })(a: $a),
        ];
    };

    $e1 = 'function (object $a): array {
        $b = $a instanceof \DateTime || $a instanceof \ReflectionClosurePhp80InstanceOfTest || $a instanceof \Tests\Fixtures\RegularClass;

        return [
            $b,
            ($a instanceof \DateTime || $a instanceof \ReflectionClosurePhp80InstanceOfTest || $a instanceof \Tests\Fixtures\RegularClass),
            (function (object $a): bool {
                return ($a instanceof \DateTime || $a instanceof \ReflectionClosurePhp80InstanceOfTest || $a instanceof \Tests\Fixtures\RegularClass) === true;
            })(a: $a),
        ];
    }';

    expect($f1)->toBeCode($e1);
});

test('switch statement', function () {
    $f1 = function ($a) {
        switch (true) {
            case $a === 1:
                return 'one';
            case reflection_closure_php_80_switch_statement_test_is_two(a: $a):
                return 'two';
            case ReflectionClosurePhp80SwitchStatementTest::isThree(a: $a):
                return 'three';
            case (new ReflectionClosurePhp80SwitchStatementTest)->isFour(a: $a):
                return 'four';
            case ($a instanceof ReflectionClosurePhp80SwitchStatementTest):
                return 'five';
            case ($a instanceof DateTime):
                return 'six';
            case ($a instanceof RegularClass):
                return 'seven';
            default:
                return 'other';
        }
    };

    $e1 = 'function ($a) {
        switch (true) {
            case $a === 1:
                return \'one\';
            case \reflection_closure_php_80_switch_statement_test_is_two(a: $a):
                return \'two\';
            case \ReflectionClosurePhp80SwitchStatementTest::isThree(a: $a):
                return \'three\';
            case (new \ReflectionClosurePhp80SwitchStatementTest)->isFour(a: $a):
                return \'four\';
            case ($a instanceof \ReflectionClosurePhp80SwitchStatementTest):
                return \'five\';
            case ($a instanceof \DateTime):
                return \'six\';
            case ($a instanceof \Tests\Fixtures\RegularClass):
                return \'seven\';
            default:
                return \'other\';
        }
    }';

    expect($f1)->toBeCode($e1);
});

function reflection_closure_php_80_match_statement_test_is_two($a)
{
    return $a === 2;
}

class ReflectionClosurePhp80MatchStatementTest
{
    public static function isThree($a)
    {
        return $a === 3;
    }

    public function isFour($a)
    {
        return $a === 4;
    }
}

test('match statement', function () {
    $f1 = function ($a) {
        return match (true) {
            $a === 1 => 'one',
            reflection_closure_php_80_match_statement_test_is_two($a) => 'two',
            ReflectionClosurePhp80MatchStatementTest::isThree(a: $a) => 'three',
            (new ReflectionClosurePhp80MatchStatementTest)->isFour($a) => 'four',
            $a instanceof ReflectionClosurePhp80MatchStatementTest => 'five',
            $a instanceof DateTime => 'six',
            $a instanceof RegularClass => 'seven',
            default => 'other',
        };
    };

    $e1 = 'function ($a) {
        return match (true) {
            $a === 1 => \'one\',
            \reflection_closure_php_80_match_statement_test_is_two($a) => \'two\',
            \ReflectionClosurePhp80MatchStatementTest::isThree(a: $a) => \'three\',
            (new \ReflectionClosurePhp80MatchStatementTest)->isFour($a) => \'four\',
            $a instanceof \ReflectionClosurePhp80MatchStatementTest => \'five\',
            $a instanceof \DateTime => \'six\',
            $a instanceof \Tests\Fixtures\RegularClass => \'seven\',
            default => \'other\',
        };
    }';

    expect($f1)->toBeCode($e1);
});
