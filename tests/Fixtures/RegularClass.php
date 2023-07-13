<?php

namespace Tests\Fixtures;

class RegularClass
{
    public const C = 'CONST';

    public function __construct(
        public $a1 = null,
        public $a2 = null,
        public $a3 = null,
        public $a4 = null,
        public $a5 = null,
        public $a6 = null,
        public $a7 = null,
        public $a8 = null,
        public $a9 = null,
        public $a10 = null,
        public $a11 = null,
        public $a12 = null,
        public $a13 = null,
        public $a14 = null,
        public $a15 = null,
        public $a16 = null,
        public $a17 = null,
        public $a18 = null,
        public $a19 = null,
        public $a20 = null,
    ) {
    }

    public static function staticMethod()
    {
        return 'staticMethod';
    }

    public function instanceMethod()
    {
        return 'instanceMethod';
    }
}
