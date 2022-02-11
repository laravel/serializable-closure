<?php

namespace Tests\Fixtures;

class Model
{
    public function make(Model $model): Model
    {
        return new Model();
    }

    public static function staticMake(Model $model): Model
    {
        return new Model();
    }
}
