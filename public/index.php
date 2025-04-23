<?php

//phpinfo();

/*$server   = "192.168.1.110,58021";
$database = "master";
$username = "sa";
$password = "2311250128";

try {
    $conn = new PDO("sqlsrv:Server=$server;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexão com SQL Server estabelecida com sucesso!";
} catch (PDOException $e) {
    echo "❌ Erro na conexão: " . $e->getMessage();
}*/

require __DIR__ . '/../vendor/autoload.php';

use ScoobEco\InitSystem\Boot;

ini_set('display_errors', 0);
error_reporting(E_ALL);

/* Start system ScoobEco */
new Boot();