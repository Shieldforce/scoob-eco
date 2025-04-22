<?php

namespace ScoobEco\Controllers;

use ScoobEco\Core\Http\Request;

class HomeController
{
    public function index(Request $request)
    {
        dd($request);
    }
}