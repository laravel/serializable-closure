<?php

namespace Laravel\SerializableClosure\Exceptions;

use Opis\Closure\SecurityException;

class InvalidSignatureException extends SecurityException
{
    /**
     * Create a new exception instance.
     *
     * @param string $message
     *
     * @return void
     */
    public function __construct($message = 'Your serialized closure might have been modified or it\'s unsafe to be unserialized.')
    {
        parent::__construct($message);
    }
}
