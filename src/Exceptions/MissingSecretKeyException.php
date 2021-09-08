<?php

namespace Laravel\SerializableClosure\Exceptions;

use Opis\Closure\SecurityException;

class MissingSecretKeyException extends SecurityException
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     *
     * @return void
     */
    public function __construct($message = 'No serializable closure secret key has been specified.')
    {
        parent::__construct($message);
    }
}
