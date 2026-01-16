<?php

it('can serialize class with virtual properties', function () {
    $binding = new ClosureBinding();

    $f = $binding->closure;

    $s1 = s($f);

    $result = $s1();

    expect($result->virtualString)->toBe('virtual string')
        ->and($result->virtualArray)->toBe(['virtual array'])
        ->and($result->virtualObject)->toBeInstanceOf(VirtualObjectWithPhp84::class);
})->with('serializers');

/**
 * Has a bound closure.
 *
 * This bound closure is needed to test `wrapClosures()` of `Native` and ensure it handles
 * virtual properties correctly.
 */
class ClosureBinding {
    public Closure $closure {
        get {
            $virtualProps = new VirtualPropWithPhp84();

            return function () use ($virtualProps) {
                // This binds the closure to `$this`
                $this;

                return $virtualProps;
            };
        }
    }
}

class VirtualPropWithPhp84 {
    public string $virtualString {
        get => 'virtual string';
    }

    public array $virtualArray {
        get => ['virtual array'];
    }

    public VirtualObjectWithPhp84 $virtualObject {
        get => new VirtualObjectWithPhp84();
    }
}

class VirtualObjectWithPhp84 {}
