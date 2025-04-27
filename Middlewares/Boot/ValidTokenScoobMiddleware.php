<?php

namespace ScoobEco\Middlewares\Boot;

use ScoobEco\Core\Http\MiddlewareInterface;
use ScoobEco\Core\Http\Request;
use ScoobEco\Core\Http\Response;
use ScoobEco\Enum\ResponseType;

class ValidTokenScoobMiddleware implements MiddlewareInterface
{
    public function handle(Request $request)
    {
        if (
            $request->method == "POST" &&
            (
                !isset($request->params["_token"]) ||
                $request->params["_token"] != "teste"
            )
        ) {
            Response::return(
                $request,
                ResponseType::error,
                "Scoob Token not found! variable: _token",
                509
            );
        }
    }
}