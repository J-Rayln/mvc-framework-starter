<?php

namespace JonathanRayln\UdemyClone\Http;

class Response
{
    public const NOT_FOUND = 404;

    public function setResponseCode(int $code): void
    {
        http_response_code($code);
    }

    public function redirectTo($path = ''): void
    {
        header('Location: /' . $path);
        exit();
    }
}