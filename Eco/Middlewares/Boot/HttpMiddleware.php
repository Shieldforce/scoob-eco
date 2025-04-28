<?php

namespace ScoobEco\Eco\Middlewares\Boot;

use ScoobEco\Core\Config;
use ScoobEco\Core\Http\MiddlewareInterface;
use ScoobEco\Core\Http\Request;
use ScoobEco\Core\Http\Response;
use ScoobEco\Enum\ResponseType;

class HttpMiddleware implements MiddlewareInterface
{
    public function handle(Request $request)
    {
        $domainEnv = Config::get('system.domain');
        header("Host: {$domainEnv}");

        if($request->headers["host"] != $domainEnv) {
            $msg = "Host not accept! Verify .env -> SCOOB_DOMAIN, ust be ";
            $msg .= " equal to {$request->headers["host"]}";
            Response::return(
                $request,
                ResponseType::error,
                $msg,
                403
            );
        }
    }
}