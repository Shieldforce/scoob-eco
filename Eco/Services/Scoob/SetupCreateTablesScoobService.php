<?php

namespace ScoobEco\Eco\Services\Scoob;

use Exception;
use ScoobEco\Core\Database\DB;
use ScoobEco\Core\Http\Request;
use ScoobEco\Eco\Enum\SetupScoobType;

class SetupCreateTablesScoobService implements SetupScoobInterfaceService
{
    public string $message = "";

    public function __construct(
        public Request        $request,
        public SetupScoobType $typeStep
    ) {}

    public function setup()
    {
        try {
            DB::prepare()->deleteTable("scoob_users");

            $resultTableUsers = DB::prepare()->createTable("scoob_users", [
                "id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY",
                "name VARCHAR(100) NOT NULL",
                "email VARCHAR(100) NOT NULL UNIQUE",
                "password VARCHAR(255) NOT NULL",
            ]);

            if (!$resultTableUsers) {
                throw new Exception("Erro ao criar tabelas!");
            }

            $this->message = "Tabelas criadas com sucesso!";
        } catch (Exception $exception) {
            // throw new Exception($exception->getMessage());
            throw new Exception("Erro ao criar tabelas!");
        }
    }

    public function message(): string
    {
        return $this->message;
    }
}