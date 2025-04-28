<?php

namespace ScoobEco\Core\Http;

use ScoobEco\Eco\Middlewares\OrderExecuteMiddlewares;

class BaseMiddleware
{
    private static array $middlewares = [];

    public function __construct(
        public Request $request
    ) {}

    public static function addMiddlewares(MiddlewareInterface $middleware): void
    {
        self::$middlewares[] = $middleware;
    }

    public function loadBootMiddlewares(): void
    {
        $middlewaresFiles = glob(__DIR__ . '/../../../Eco/Middlewares/Boot/*.php');
        foreach ($middlewaresFiles as $file) {
            $xPath = explode("/", $file);

            $ecoName        = $xPath[count($xPath) - 4];
            $middlewareName = $xPath[count($xPath) - 3];
            $bootName       = $xPath[count($xPath) - 2];
            $fileName       = $xPath[count($xPath) - 1];
            $join           = implode("\\", [
                $ecoName,
                $middlewareName,
                $bootName,
                $fileName,
            ]);
            $join           = str_replace([".php"], [""], $join);
            $className      = "ScoobEco\\$join";
            if (class_exists($className)) {
                self::addMiddlewares(new $className());
            }
        }
    }

    public function loadOrderMiddlewares(): void
    {
        OrderExecuteMiddlewares::run();
    }

    public function executeMiddlewares(): void
    {
        $this->loadBootMiddlewares();

        $this->loadOrderMiddlewares();

        foreach (self::$middlewares as $middleware) {
            $middleware->handle($this->request);
        }

        $routeMiddlewares = $this->request->currentRoute["middlewares"] ?? [];

        foreach ($routeMiddlewares as $routeMiddleware) {
            $routeMiddleware->handle($this->request);
        }
    }

    public function executeRouteMiddlewares($routeMiddlewares): void
    {
        foreach ($routeMiddlewares as $routeMiddleware) {
            $routeMiddleware->handle($this->request);
        }
    }
}