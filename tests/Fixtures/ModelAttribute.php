<?php

namespace Tests\Fixtures;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION)]
class ModelAttribute
{
    // ..
}
