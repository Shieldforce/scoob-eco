<?php

namespace ScoobEco\InitSystem;

use ScoobEco\Core\Config;
use ScoobEco\Core\Http\Request;
use ScoobEco\Core\Routing\Router;
use ScoobEco\Enum\ErrorType;
use ScoobEco\Exception\ErrorHandler;
use Throwable;
use ScoobEco\Core\Env;

class Boot
{
    public function __construct()
    {
        try {
            $this->loadEnv();
            $this->loadConfig();
            $this->handleRequest();

        } catch (Throwable $e) {
            ErrorHandler::handle($e, ErrorType::fromCodeOrDefault($e->getCode()));
        }
    }

    protected function loadEnv()
    {
        Env::load();
    }

    protected function loadConfig()
    {
        Config::load();
    }

    protected function handleRequest()
    {
        $request = new Request();
        $router  = new Router($request);
        $router->dispatch();
    }
}