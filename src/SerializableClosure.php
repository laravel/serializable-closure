<?php

namespace Laravel\SerializableClosure;

use Closure;
use Laravel\SerializableClosure\Signers\Hmac;
use Opis\Closure\SerializableClosure as BaseSerializableClosure;

class SerializableClosure
{
    /**
     * The closure's serializable.
     *
     * @var \Laravel\SerializableClosure\Contracts\Serializable
     */
    protected $serializable;

    /**
     * Creates a new serializable closure instance.
     *
     * @param  \Closure  $closure
     *
     * @return void
     */
    public function __construct(Closure $closure)
    {
        if ((float) phpversion() < '7.4') {
            $this->serializable = new BaseSerializableClosure($closure);
        } else {
            $this->serializable = Serializers\Signed::$signer
                ? new Serializers\Signed($closure)
                : new Serializers\Native($closure);
        }
    }

    /**
     * Resolve the closure with the given arguments.
     *
     * @return mixed
     */
    public function __invoke()
    {
        return $this->serializable->__invoke();
    }

    /**
     * Gets the closure.
     *
     * @return \Closure
     */
    public function getClosure()
    {
        return $this->serializable->getClosure();
    }

    /**
     * Sets the serializable closure secret key.
     *
     * @param  string|null  $secret
     *
     * @return void
     */
    public static function setSecretKey($secret)
    {
        if ((float) phpversion() < '7.4') {
            $secret
                ? BaseSerializableClosure::setSecretKey($secret)
                : BaseSerializableClosure::removeSecurityProvider();
        } else {
            Serializers\Signed::$signer = $secret
                ? new Hmac($secret)
                : null;
        }
    }

    /**
     * Sets the serializable closure secret key.
     *
     * @param  \Closure|null  $transformer
     *
     * @return void
     */
    public static function transformUseVariablesUsing($transformer)
    {
        Serializers\Native::$transformUseVariables = $transformer;
    }

    /**
     * Sets the serializable closure secret key.
     *
     * @param  \Closure|null  $resolver
     *
     * @return void
     */
    public static function resolveUseVariablesUsing($resolver)
    {
        Serializers\Native::$resolveUseVariables = $resolver;
    }
}
