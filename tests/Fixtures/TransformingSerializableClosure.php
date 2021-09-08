<?php

namespace Tests\Fixtures;

use Opis\Closure\SerializableClosure;

class TransformingSerializableClosure extends SerializableClosure
{
    protected function transformUseVariables($data)
    {
        foreach ($data as $key => $value) {
            $data[$key] = $value * 2;
        }

        return $data;
    }

    protected function resolveUseVariables($data)
    {
        foreach ($data as $key => $value) {
            $data[$key] = $value / 4;
        }

        return $data;
    }
}