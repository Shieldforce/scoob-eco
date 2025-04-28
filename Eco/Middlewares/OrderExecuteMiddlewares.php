<?php

namespace ScoobEco\Eco\Middlewares;

use ScoobEco\Eco\Middlewares\OrderList\FirstMiddleware;
use ScoobEco\Eco\Middlewares\OrderList\SecondMiddleware;

class OrderExecuteMiddlewares
{
    public static function run(): array
    {
        return [
            new FirstMiddleware(),
            new SecondMiddleware(),
        ];
    }
}