<?php

namespace JonathanRayln\UdemyClone\Routing;

use JonathanRayln\UdemyClone\Application;
use JonathanRayln\UdemyClone\Http\Middleware\MiddlewareResolver;
use JonathanRayln\UdemyClone\Http\Request;
use JonathanRayln\UdemyClone\Http\Response;

class Router
{
    /** @var array */
    public array $routes = [];

    public function __construct(
        public Request  $request,
        public Response $response
    ) {}

    /**
     * Adds a route to the $routes[] array.
     *
     * @param string            $method
     * @param string            $uri
     * @param array|string      $controller
     * @param string|array|null $middleware
     * @return $this
     */
    public function addRoute(string            $method,
                             string            $uri,
                             array|string      $controller,
                             string|array|null $middleware = MiddlewareResolver::DEFAULT): static
    {
        $this->routes[] = [
            'method'     => $method,
            'uri'        => $uri,
            'controller' => $controller,
            'middleware' => $middleware
        ];

        return $this;
    }

    /**
     * Resolves a route or renders the 404 template if it cannot be found.
     *
     * @returns void
     */
    public function resolve(): void
    {
        $method = $this->request->getMethod();
        $uri = $this->request->getUri();

        // Iterate over registered routes
        foreach ($this->routes as $route) {

            if ($route['method'] === $method) {
                $routePath = $route['uri'];
                $routeNames = [];

                // Find all route names from $route and save in $routeNames
                if (preg_match_all('/\{(\w+)(:[^}]+)?}/', $routePath, $matches)) {
                    $routeNames = $matches[1];
                }

                // Convert route names into regex pattern
                $routeRegex = "@^" . preg_replace_callback('/\{\w+(:([^}]+))?}/', fn($m) => isset($m[2]) ? "({$m[2]})" : '([\w-]+)', $route['uri']) . "$@";

                // Test and match current route against $routeRegex
                if (preg_match_all($routeRegex, $uri, $valueMatches)) {

                    $values = [];
                    for ($i = 1; $i < count($valueMatches); $i++) {
                        $values[] = $valueMatches[$i][0];
                    }

                    $routeParams = array_combine($routeNames, $values);

                    $controller = new $route['controller'][0]($this->request, $this->response);
                    Application::$app->controller = $controller;
                    $route['controller'][0] = $controller;
                    $action = $route['controller'][1];

                    MiddlewareResolver::resolve($route['middleware']);

                    call_user_func_array([$controller, $action], $routeParams);
                    return;
                }
            }
        }

        $this->response->redirectTo('404', Response::NOT_FOUND);

        Application::$app->layout = 'exception';
        echo Application::$app->view->renderTemplate('exceptions/404');
    }
}