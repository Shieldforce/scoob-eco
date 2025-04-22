<?php

namespace ScoobEco\Core\Database;

use ScoobEco\InitSystem\DB\ScoobDB;

class DB {
    public static function prepare() {
        return new ScoobDB();
    }
}