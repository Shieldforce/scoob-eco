<?php

namespace ScoobEco\Eco\Controllers;

use ScoobEco\Core\Controllers\BaseController;
use ScoobEco\Core\Http\Request;

class HomeController extends BaseController
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