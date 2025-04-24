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

    /*public function dispatch()
    {
        $requestUri = '/' . trim($this->request->uri, '/');

        foreach ($this->routes as $route => $array) {
            $paramNames = [];

            $route = '/' . trim($route, '/');

            $pattern = preg_replace_callback('#\{([^}/]+)\??\}#', function ($matches) use (&$paramNames) {
                $isOptional = str_ends_with($matches[0], '?}');
                $paramName = rtrim($matches[1], '?');
                $paramNames[] = $paramName;

                return $isOptional ? '(?:/([^/]+))?' : '/([^/]+)';
            }, $route);


            $pattern = preg_replace('#//+#', '/', $pattern);
            $pattern = "#^" . rtrim($pattern, '/') . "/?$#";

            $requestUri = trim($requestUri);

            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches);

                $params = [];
                foreach ($paramNames as $i => $name) {
                    $params[$name] = $matches[$i] ?? null;
                }

                [
                    $controller,
                    $method
                ] = explode('@', $array["action"]);
                return (new $controller)->$method($this->request, ...array_values($params));
            }
        }

        throw new Exception("Route not found!", 404);
    }*/


    public function dispatch()
    {
        $requestUri = '/' . trim($this->request->uri, '/');
        foreach ($this->routes as $route => $array) {
            $verify = $this->verifySegments($requestUri, $route);
            if($verify["ok"]) {
                [
                    $controller,
                    $method
                ] = explode('@', $array["action"]);
                return (new $controller)->$method($this->request, ...array_values($verify["params"] ?? []));
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
        $uriSegments   = array_values(array_filter(explode('/', trim($requestUri, '/'))));
        $routeSegments = array_values(array_filter(explode('/', trim($route, '/'))));

        // Se a URI for maior que a rota, só continua se os extras forem parâmetros opcionais
        if (count($uriSegments) > count($routeSegments)) {
            return ["ok" => false];
        }

        $params = [];

        foreach ($routeSegments as $index => $segment) {
            $uriValue = $uriSegments[$index] ?? null;

            $isOptionalParam = preg_match('/^{([^\/?]+)\?}$/', $segment, $optionalMatch);
            $isRequiredParam = preg_match('/^{([^\/?]+)}$/', $segment, $requiredMatch);

            if (!$isRequiredParam && !$isOptionalParam) {
                // Segmento fixo
                if ($segment !== $uriValue) {
                    return ["ok" => false];
                }
            }

            if ($isRequiredParam) {
                $paramName = $requiredMatch[1];
                if ($uriValue === null) {
                    return ["ok" => false];
                }
                $params[$paramName] = $uriValue;
            }

            if ($isOptionalParam) {
                $paramName = $optionalMatch[1];
                if ($uriValue !== null) {
                    $params[$paramName] = $uriValue;
                }
            }
        }

        return ["ok" => true, "params" => $params];
    }


}
