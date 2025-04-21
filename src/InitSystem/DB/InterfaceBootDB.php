<?php

namespace ScoobEco\InitSystem\DB;

use PDO;

interface InterfaceBootDB
{
    public static function getConnection(): PDO;
}