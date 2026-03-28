<?php

it('can serialize closures from methods with method-only attributes', function () {
    $closure = (new ChildClassWithOverride)->test(...);

    $unserialized = s($closure);

    expect($unserialized())->toBe('hello');
})->with('serializers');

it('preserves attributes that can target functions', function () {
    $closure = (new ClassWithFunctionTargetableAttribute)->test(...);

    $code = (new \Laravel\SerializableClosure\Support\ReflectionClosure($closure))->getCode();

    expect($code)->toContain('FunctionTargetableAttribute');
})->with('serializers');

#[\Attribute(\Attribute::TARGET_METHOD)]
class MethodOnlyAttribute
{
}

#[\Attribute(\Attribute::TARGET_FUNCTION | \Attribute::TARGET_METHOD)]
class FunctionTargetableAttribute
{
}

class ParentClassForOverride
{
    public function test(): string
    {
        return '';
    }
}

class ChildClassWithOverride extends ParentClassForOverride
{
    #[\Override]
    public function test(): string
    {
        return 'hello';
    }
}

class ClassWithFunctionTargetableAttribute
{
    #[FunctionTargetableAttribute]
    public function test(): string
    {
        return 'world';
    }
}
