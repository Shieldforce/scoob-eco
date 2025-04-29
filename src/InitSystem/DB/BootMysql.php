<?php

namespace ScoobEco\InitSystem\DB;

use PDO;
use ScoobEco\Core\Config;

class BootMysql implements InterfaceBootDB
{
    private static ?PDO $connection = null;

    private function __construct() {}

    public function __clone() {}

    public function __wakeup() {}

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $host    = Config::get('database.connections.mysql.host');
            $user    = Config::get('database.connections.mysql.user');
            $pass    = Config::get('database.connections.mysql.pass');
            $db      = Config::get('database.connections.mysql.db');
            $charset = Config::get('database.connections.mysql.charset');

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

    public static function disconnect(): void
    {
        self::$connection = null;
    }
}
