<?php

namespace ScoobEco\InitSystem;

use ScoobEco\Core\Database\DB;
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
            $users = DB::prepare()->table("users")->get();
            dd($users);

        } catch (Throwable $e) {
            ErrorHandler::handle($e, ErrorType::fromCodeOrDefault($e->getCode()));
        }
    }
    protected function loadEnv()
    {
        Env::load();
    }
}