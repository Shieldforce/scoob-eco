<?php

namespace ScoobEco\InitSystem\DB;

use PDO;

class ScoobDB
{
    public static function connection(): PDO
    {
        $connection = null;

        $driver = $_ENV['DB_DRIVER'];

        if ($driver == 'mysql') {
            $connection = BootMysql::getConnection();
        }

        if ($driver == 'sqlite') {
            echo "sqlite";
        }

        if ($driver == 'sqlsrv') {
            echo "sqlite";
        }

        return $connection;
    }

    public static function getTables(string $database): array|string {
        $db = self::connection()->query("SHOW TABLES;");
        $result = $db->fetchAll(PDO::FETCH_ASSOC);
        $result = array_column($result, 'Tables_in_' . $database);
        return  $result ?? [];
    }

    public static function getDatabase(): string|null {
        $db = self::connection()->query("SELECT DATABASE();");
        $result = $db->fetch(PDO::FETCH_ASSOC);
        return  $result['DATABASE()'] ?? null;
    }
}