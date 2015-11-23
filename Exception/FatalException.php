<?php

namespace Fruitware\GabrielApi\Exception;

/**
 * Exception, after which it is impossible to continue working with the current Gabriel session
 */
class FatalException extends GabrielException
{
    const ERROR_INVALID_OPERATION = -1;
    const ERROR_SESSION_EXPIRED = 13;
}