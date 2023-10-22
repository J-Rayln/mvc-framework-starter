<?php

namespace JonathanRayln\UdemyClone\Routing\Exceptions;

use Exception;
use Throwable;

class BadMethodCallException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}