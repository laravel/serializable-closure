<?php

use Laravel\SerializableClosure\Serializers\Native;

test('anonymous class static closure without scope requirements omits scope and round-trips', function (): void {
    $factory = new class
    {
        public function makeClosure(): Closure
        {
            return static function (): string {
                return 'serialized';
            };
        }
    };

    $native = new Native($factory->makeClosure());
    $data = $native->__serialize();

    expect($data['scope'])->toBeNull();

    expect(s($factory->makeClosure())())->toBe('serialized');
})->with('serializers');

test('anonymous class closure keeps scope when it is required', function (): void {
    $factory = new class
    {
        public function makeClosure(): Closure
        {
            return static function (): string {
                return self::class;
            };
        }
    };

    $native = new Native($factory->makeClosure());
    $data = $native->__serialize();

    expect($data['scope'])
        ->toBeString()
        ->toContain('@anonymous');
});
