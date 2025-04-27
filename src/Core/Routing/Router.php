<?php

namespace ScoobEco\Core\Routing;

use Exception;
use ScoobEco\Core\Http\BaseMiddleware;
use ScoobEco\Core\Http\Request;
use Throwable;

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
        $requestUri = '/' . trim($this->request->uri, '/');
        foreach ($this->routes as $route => $array) {
            $route = [
                "path" => $route,
            ];

            $route = array_merge($route, $array);

            if (isset($route["middlewares"]) && count($route["middlewares"]) > 0) {
                $baseMiddleware = new BaseMiddleware($this->request);
                $baseMiddleware->executeRouteMiddlewares($route["middlewares"]);
            }

            $verify = $this->verifySegments($requestUri, $route);
            if ($verify["ok"]) {
                $this->request->currentRoute = $route;
                return $this->verifyControllerExit($array, $verify);
            }
        }
        throw new Exception("Route not found!", 404);
    }

    public static function getRoutes()
    {
        return (new Router(self::$someRequest))->routes;
    }

    public function verifySegments($requestUri, $route)
    {
        if (strtoupper($this->request->method) != strtoupper($route["method"])) {
            return ["ok" => false];
        }

        $newRoutePath        = $route["path"];
        $params["variables"] = [];
        $params["match"]     = [];

        $patterParams = "/\/?{(.*?)\??}/";
        if (preg_match_all($patterParams, $route["path"], $matches)) {
            $newRoutePath        = preg_replace($patterParams, '(?:/(.*))?', $route["path"]);
            $params["match"]     = $matches[1];
            $params["variables"] = $matches[0];
        }

        $patternRoute = "/^" . str_replace("/", "\/", $newRoutePath) . "$/";

        if (preg_match($patternRoute, $requestUri, $matches)) {

            unset($matches[0]);

            $keys          = $params["match"];
            $matchesParams = explode("/", $matches[1]);
            $params        = $this->matchArrayCombine($matchesParams, $keys);

            return [
                "ok"     => true,
                "params" => $params
            ];
        }

        return ["ok" => false];
    }

    public function verifyControllerExit(array $array, array $verify)
    {
        [
            $controller,
            $method
        ] = explode('@', $array["action"]);


        if (!class_exists($controller)) {
            throw new Exception("Controller not found: {$controller}", 404);
        }

        if (!method_exists($controller, $method)) {
            throw new Exception("Method not found: {$controller} -> {$method}", 404);
        }

        return (new $controller)
            ->$method(
                $this->request,
                ...array_values($verify["params"] ?? []
                )
            );
    }

    public function matchArrayCombine($matchesParams, $keys)
    {
        try {
            $match = (count($keys) > 0 && count($matchesParams) > 0) ?
                array_combine($keys, $matchesParams) :
                [];
            return $match;
        } catch (Throwable $exception) {
            throw new Exception("Route not found!", 404);
        }
    }

}
