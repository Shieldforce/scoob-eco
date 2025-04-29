<?php

namespace ScoobEco\Eco\Controllers;

use Exception;
use ScoobEco\Core\Controllers\BaseController;
use ScoobEco\Core\Database\DB;
use ScoobEco\Core\Http\Request;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
        $title = "Home";

        $user = null;

        try {
            $user = DB::prepare()->table("scoob_users")->find(1);
        } catch (Exception $e) {}

        return view(
            'pages.home.index',
            compact('title', 'user')
        );
    }
}