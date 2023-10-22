<?php

namespace JonathanRayln\UdemyClone\Routing;

use JonathanRayln\UdemyClone\Application;
use JonathanRayln\UdemyClone\Http\Middleware\MiddlewareResolver;

class Route
{
    public static function get(string $uri, string|array $controller, string|array|null $middleware = MiddlewareResolver::DEFAULT): Router
    {
        return Application::$app->router->addRoute('GET', $uri, $controller, $middleware);
    }

    public static function post(string $uri, string|array $controller, string|array|null $middleware = MiddlewareResolver::DEFAULT): Router
    {
        return Application::$app->router->addRoute('POST', $uri, $controller, $middleware);
    }

    public static function put(string $uri, string|array $controller, string|array|null $middleware = MiddlewareResolver::DEFAULT): Router
    {
        return Application::$app->router->addRoute('PUT', $uri, $controller, $middleware);
    }

    public static function patch(string $uri, string|array $controller, string|array|null $middleware = MiddlewareResolver::DEFAULT): Router
    {
        return Application::$app->router->addRoute('PATCH', $uri, $controller, $middleware);
    }

    public static function delete(string $uri, string|array $controller, string|array|null $middleware = MiddlewareResolver::DEFAULT): Router
    {
        return Application::$app->router->addRoute('DELETE', $uri, $controller, $middleware);
    }
}