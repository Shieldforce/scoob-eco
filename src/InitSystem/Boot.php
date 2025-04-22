<?php

namespace ScoobEco\InitSystem;

use ScoobEco\Enum\ErrorType;
use ScoobEco\Exception\ErrorHandler;
use Throwable;

class Boot
{
    public function __construct()
    {
        try {
            //

        } catch (Throwable $e) {
            ErrorHandler::handle($e, ErrorType::fromCodeOrDefault($e->getCode()));
        }
    }

}