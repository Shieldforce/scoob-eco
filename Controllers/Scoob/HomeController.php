<?php

namespace ScoobEco\Controllers\Scoob;

use ScoobEco\Core\Controllers\BaseController;
use ScoobEco\Core\Http\Request;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
        $title = "Login ScoobEco";

        return view(
            'pages.scoob.login',
            compact('title')
        );

    }
}