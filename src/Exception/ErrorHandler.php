<?php

namespace ScoobEco\Exception;

use ScoobEco\Enum\ErrorType;
use Throwable;

class ErrorHandler
{
    public static function handle(
        Throwable $e,
        ErrorType $type = ErrorType::FATAL_ERROR
    ): void
    {
        ddError($type, $e);
    }
}
