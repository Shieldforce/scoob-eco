<?php

namespace ScoobEco\Core\Routing;

use Exception;
use ScoobEco\Core\Http\Request;

class Router
{
    protected Request $request;
    protected array   $routes = [];
    protected static  $someRequest;

    public function __construct(Request $request)
    {
        $this->request     = $request;
        self::$someRequest = $request;
        $this->loadRoutes();
    }

    protected function loadRoutes()
    {
        $this->routes = [];

        $routeFiles = glob(__DIR__ . '/../../../routes/*.php');

        foreach ($routeFiles as $file) {
            $routes = require $file;

            if (is_array($routes)) {
                $this->routes = array_merge($this->routes, $routes);
            }
        }
    }

    public function dispatch()
    {
        foreach ($this->routes as $route => $array) {
            if ($route === $this->request->uri) {
                [
                    $controller,
                    $method
                ] = explode('@', $array["action"]);
                return (new $controller)->$method($this->request);
            }
        }

        throw new Exception("Route not found!", 404);
    }

    public static function getRoutes()
    {
        return (new Router(self::$someRequest))->routes;
    }
}
