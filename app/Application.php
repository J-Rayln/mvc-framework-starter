<?php

namespace JonathanRayln\UdemyClone;

use JonathanRayln\UdemyClone\Database\Database;
use JonathanRayln\UdemyClone\Http\Request;
use JonathanRayln\UdemyClone\Http\Response;
use JonathanRayln\UdemyClone\Routing\Controller;
use JonathanRayln\UdemyClone\Routing\Router;
use JonathanRayln\UdemyClone\Session\Session;
use JonathanRayln\UdemyClone\View\View;

class Application
{
    public static Application $app;
    public static string $ROOTPATH;

    public Session $session;
    public Database $db;
    public Request $request;
    public Response $response;
    public Router $router;
    public string $layout = View::DEFAULT_LAYOUT;
    public ?Controller $controller = null;
    public View $view;

    public function __construct(array $config, string $rootPath)
    {
        self::$app = $this;
        self::$ROOTPATH = $rootPath;

        $this->session = new Session();
        $this->db = new Database();
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->view = new View();
    }

    public function run(): void
    {
        $this->bootstrap();
        $this->router->resolve();
    }

    private function bootstrap(): void
    {
        if (php_sapi_name() === 'cli')
            die('This application is intended to be run from the web browser.  CLI support is not offered at this time.');

        foreach (glob($this::$ROOTPATH . '/App/helpers/*.php') as $resource)
            include_once $resource;

        require_once $this::$ROOTPATH . '/routes.php';
    }
}