<?php

namespace ScoobEco\DIContainer;

class Teste2 {

    public string $variable;

    public function __construct(private Teste1 $teste1) {}

    public function run()
    {
        var_dump($this->teste1);
    }

}