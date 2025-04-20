<?php

namespace ScoobEco\InitSystem;

use PDO;
use PDOException;

class Boot
{
    public function __construct()
    {
        // Agora você pode acessar assim:
        $host    = $_ENV['DB_HOST'];
        $user    = $_ENV['DB_USER'];
        $pass    = $_ENV['DB_PASS'];
        $db      = $_ENV['DB_NAME'];
        $charset = $_ENV['DB_CHARSET'];

        try {
            $dsn     = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $pdo = new PDO($dsn, $user, $pass, $options);

            echo "✅ Conexão com banco de dados realizada com sucesso!";
        } catch (PDOException $e) {
            var_dump($e->getMessage());
            echo "❌ Erro ao conectar com o banco: " . $e->getMessage();
            exit;
        }
    }

}