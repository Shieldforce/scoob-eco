<?php

namespace ScoobEco\InitSystem\DB;

use PDO;

class BootSqlite implements InterfaceBootDB
{
    private static ?PDO $connection = null;

    private function __construct() {}

    public static function getConnection(): PDO
    {
        return self::$connection;
    }

    public function __clone() {}

    public function __wakeup() {}
}
