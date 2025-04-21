<?php

namespace ScoobEco\InitSystem;

use ScoobEco\Enum\ErrorType;
use ScoobEco\Exception\ErrorHandler;
use ScoobEco\InitSystem\DB\ScoobDB;
use Throwable;

class Boot
{
    public function __construct()
    {
        try {
            $scoobDB = new ScoobDB();
            $scoobDB->connection();

        } catch (Throwable $e) {
            ErrorHandler::handle($e, ErrorType::Database);
        }
    }

}