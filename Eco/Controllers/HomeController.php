<?php

namespace ScoobEco\Eco\Controllers;

use ScoobEco\Core\Http\Request;

class HomeController
{
    public function index(Request $request)
    {
        $title = "Home";
        return view(
            'pages.home.index',
            compact('title')
        );
    }
}