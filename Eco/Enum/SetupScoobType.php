<?php

namespace ScoobEco\Eco\Enum;

use ScoobEco\Eco\Services\Scoob\SetupCreateTablesScoobService;
use ScoobEco\Eco\Services\Scoob\SetupInsertDataTablesScoobService;

enum SetupScoobType: int
{
    case setup_1 = 1;
    case setup_2 = 2;

    public function message(): string
    {
        return match ($this) {
            self::setup_1 => 'Criando Tabelas',
            self::setup_2 => 'Populando Tabelas',
        };
    }

    public function classNew()
    {
        return match ($this) {
            self::setup_1 => SetupCreateTablesScoobService::class,
            self::setup_2 => SetupInsertDataTablesScoobService::class,
        };
    }
}
