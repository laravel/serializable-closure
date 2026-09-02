<?php

namespace Tests\Fixtures;

use ArrayObject;

/**
 * A userland subclass of an internal class that stores its state internally
 * rather than in userland properties. Rebuilding it property by property
 * silently drops the internal storage.
 */
class CustomCollection extends ArrayObject
{
    //
}
