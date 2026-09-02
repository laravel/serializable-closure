<?php

namespace Tests\Fixtures;

use Carbon\CarbonImmutable;
use Closure;

/**
 * Simulates a class like an Eloquent model that holds a Carbon date and is
 * captured by a closure (e.g. via Bus::chain), requiring the library to
 * serialize an object whose class inherits from a PHP internal class.
 */
class ClassWithCarbonProperty
{
    public CarbonImmutable $paidAt;

    public function __construct(CarbonImmutable $paidAt)
    {
        $this->paidAt = $paidAt;
    }

    public function makeClosure(): Closure
    {
        return fn () => $this->paidAt->toIso8601String();
    }
}
