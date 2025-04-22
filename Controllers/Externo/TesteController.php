<?php

namespace ScoobEco\Controllers\Externo;

use ScoobEco\Core\Http\Request;

class TesteController
{
    public function teste(Request $request) {
        dd($request);
    }
}