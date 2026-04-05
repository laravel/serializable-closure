<?php

namespace Tests\Fixtures;

class SameLineClosures
{
    public static function threeIdentical(): array
    {
        return [fn () => 'a', fn () => 'b', fn () => 'c'];
    }
}
