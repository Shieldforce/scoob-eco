<?php

namespace ScoobEco\Eco\Services\Scoob;

use Exception;
use ScoobEco\Core\Config;
use ScoobEco\Core\Database\DB;
use ScoobEco\Core\Http\Request;
use ScoobEco\Core\SimpleCrypt;
use ScoobEco\Eco\Enum\SetupScoobType;

class SetupInsertDataTablesScoobService implements SetupScoobInterfaceService
{
    public string $message = "";

    public function __construct(
        public Request        $request,
        public SetupScoobType $typeStep
    ) {}

    public function setup()
    {
        try {
            DB::prepare()->table("scoob_users")->delete(1);

            $resultInsertUsers = DB::prepare()->table("scoob_users")->insertMany([
                [
                    "name"     => "Alexandre Ferreira",
                    "email"    => "admin@scoob.com.br",
                    "password" => SimpleCrypt::encrypt(
                        Config::get('database.connections.mysql.pass')
                    ),
                ]
            ]);

            if (!$resultInsertUsers) {
                throw new Exception("Erro ao popular dados nas tabelas!");
            }

            $this->message = "Dados populados com sucesso!";
        } catch (Exception $exception) {
            // throw new Exception($exception->getMessage());
            throw new Exception("Erro ao popular dados nas tabelas!");
        }
    }

    public function message(): string
    {
        return $this->message;
    }
}