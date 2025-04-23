<?php

namespace ScoobEco\Core\Routing;

use Exception;
use ScoobEco\Core\Http\Request;

class Router
{
    protected Request $request;
    protected array $routes = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
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
        foreach ($this->routes as $route => $action) {
            if ($route === $this->request->uri) {
                [$controller, $method] = explode('@', $action);
                return (new $controller)->$method($this->request);
            }
        }

        throw new Exception("Route not found!", 404);
    }
}
