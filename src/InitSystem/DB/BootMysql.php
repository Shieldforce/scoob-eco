<?php

namespace ScoobEco\InitSystem\DB;

use PDO;

class BootMysql implements InterfaceBootDB
{
    private static ?PDO    $connection   = null;

    private function __construct() {}

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $host    = $_ENV['DB_HOST'];
            $user    = $_ENV['DB_USER'];
            $pass    = $_ENV['DB_PASS'];
            $db      = $_ENV['DB_NAME'];
            $charset = $_ENV['DB_CHARSET'];

            $dsn     = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            self::$connection = new PDO($dsn, $user, $pass, $options);
        }

        return self::$connection;
    }

    public function __clone() {}

    public function __wakeup() {}
}
