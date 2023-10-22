<?php

namespace JonathanRayln\UdemyClone\Http\Middleware;

class MiddlewareResolver
{
    public const DEFAULT = null;

    public const MAP = [
        'sample' => Sample::class,
    ];

    /**
     * @param string|array|null $key
     * @throws \Exception
     */
    public static function resolve(string|array|null $key = null): void
    {
        if (!$key) {
            return;
        }

        $middleware = static::MAP[$key] ?? false;

        if (!$middleware) {
            throw new \Exception('No matching middleware for key "' . $key . '"');
        }

        (new $middleware)->handle();
    }
}