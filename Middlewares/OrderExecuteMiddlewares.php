<?php

namespace ScoobEco\Middlewares;

use ScoobEco\Middlewares\OrderList\FirstMiddleware;
use ScoobEco\Middlewares\OrderList\SecondMiddleware;

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