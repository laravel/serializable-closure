<?php

// Fake
use Some\ClassName as ClassAlias;

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
})->with('serializers');

test('single named argument within closures', function () {
    $f1 = function () {
        return (new ReflectionClosurePhp80NamedArguments)->publicMethod(namedArgument: 'string');
    };

    $e1 = "function () {
        return (new \ReflectionClosurePhp80NamedArguments)->publicMethod(namedArgument: 'string');
    }";

    expect($f1)->toBeCode($e1);
})->with('serializers');

test('multiple named arguments within closures', function () {
    $f1 = function () {
        return (new ReflectionClosurePhp80NamedArguments)->publicMethod(namedArgument: 'string', namedArgumentB: 1);
    };

    $e1 = "function () {
        return (new \ReflectionClosurePhp80NamedArguments)->publicMethod(namedArgument: 'string', namedArgumentB: 1);
    }";

    expect($f1)->toBeCode($e1);
})->with('serializers');

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
