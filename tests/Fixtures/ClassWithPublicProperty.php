<?php

declare(strict_types=1);

namespace Tests\Fixtures;

class ClassWithPublicProperty
{
    public $closure;

    public function __construct($closure)
    {
        $this->closure = $closure;
    }
}
