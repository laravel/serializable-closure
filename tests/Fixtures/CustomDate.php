<?php

namespace Tests\Fixtures;

use DateTimeImmutable;

/**
 * A userland subclass of a PHP internal class. Its internal state cannot be
 * rebuilt by newInstanceWithoutConstructor(), so the serializer must keep
 * instances as-is instead of reconstructing them property by property.
 */
class CustomDate extends DateTimeImmutable
{
    //
}
