<?php

namespace ScoobEco\InitSystem;

use ScoobEco\Core\Config;
use ScoobEco\Core\Env;
use ScoobEco\Core\Http\BaseMiddleware;
use ScoobEco\Core\Http\Request;
use ScoobEco\Core\Routing\Router;
use ScoobEco\Enum\ErrorType;
use ScoobEco\Exception\ErrorHandler;
use Throwable;

class Boot
{
    private Request $request;
    private Router  $router;

    public function __construct()
    {
        try {
            $this->loadEnv();
            $this->loadConfig();
            $this->handleRequest();
            $this->loadMiddlewares();
            $this->handleRoute();

        } catch (Throwable $e) {
            ErrorHandler::handle(
                $e,
                ErrorType::fromCodeOrDefault($e->getCode())
            );
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
        $this->request = new Request();
    }

    protected function handleRoute()
    {
        $this->router = new Router($this->request);
        $this->router->dispatch();
    }

    protected function loadMiddlewares(): void
    {
        $middleware = new BaseMiddleware($this->request);
        $middleware->executeMiddlewares();
    }
}