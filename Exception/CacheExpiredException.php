<?php

namespace Fruitware\GabrielApi\Exception;

class CacheExpiredException extends FatalException
{
    public function __construct($message = 'Cache expired', \Exception $previous = null)
    {
        parent::__construct($message, static::ERROR_SESSION_EXPIRED, $previous);
    }
}