<?php

return [
    // Setup ---
    "/scoob-eco/admin/setup"     => [
        "action"      => "ScoobEco\Eco\Controllers\Scoob\HomeController@setup",
        "name"        => "pages.scoob.setup",
        "method"      => "get",
        "middlewares" => []
    ],
    "/scoob-eco/admin/setup/run" => [
        "action"      => "ScoobEco\Eco\Controllers\Scoob\HomeController@setupRun",
        "name"        => "pages.scoob.setup.run",
        "method"      => "post",
        "middlewares" => [
            new \ScoobEco\Eco\Middlewares\Routes\ClearFieldsMiddleware()
        ]
    ],
    // Login ---
    "/scoob-eco/admin/login"     => [
        "action"      => "ScoobEco\Eco\Controllers\Scoob\HomeController@login",
        "name"        => "pages.scoob.login",
        "method"      => "get",
        "middlewares" => []
    ],
    "/scoob-eco/admin/login/run" => [
        "action"      => "ScoobEco\Eco\Controllers\Scoob\HomeController@loginRun",
        "name"        => "pages.scoob.login.run",
        "method"      => "post",
        "middlewares" => [
            new \ScoobEco\Eco\Middlewares\Routes\ClearFieldsMiddleware()
        ]
    ],
];