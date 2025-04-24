<?php

namespace ScoobEco\Core\Support;

use ScoobEco\Core\Routing\Router;

class RouteConverter
{
    public static function run(string $routeName): array
    {
        $routes  = Router::getRoutes();
        $returns = [];
        foreach ($routes ?? [] as $index => $route) {
            if ($route["name"] == $routeName) {
                $returns[$routeName] = [
                    "name"   => $route["name"],
                    "action" => $route["action"],
                    "uri"    => $index,
                ];
            }
        }
        return $returns[$routeName] ?? $returns;
    }
}
