<?php

namespace Tests\Fixtures;

class ClassWithSerializableClosureProperty
{
    public $closure;

    public function __construct($closure)
    {
        $this->closure = $closure;
    }
}
