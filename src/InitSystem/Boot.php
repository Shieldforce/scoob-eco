<?php

namespace ScoobEco\InitSystem;

class Boot
{
    public function __construct()
    {
        // Agora você pode acessar assim:
        $host = $_ENV['DB_HOST'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];
        $name = $_ENV['DB_NAME'];

        var_dump([
            "host" => $host,
            "user" => $user,
            "pass" => $pass,
            "name" => $name,
        ]);
    }

}