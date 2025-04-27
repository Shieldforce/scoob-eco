<?php

return [
    "/scoob-eco/admin/login" => [
        "action"      => "ScoobEco\Controllers\Scoob\HomeController@login",
        "name"        => "pages.scoob.login",
        "method"      => "get",
        "middlewares" => []
    ],
    "/scoob-eco/admin/run"   => [
        "action"      => "ScoobEco\Controllers\Scoob\HomeController@loginRun",
        "name"        => "pages.scoob.login.run",
        "method"      => "post",
        "middlewares" => [
            new \ScoobEco\Middlewares\Routes\ClearFieldsMiddleware()
        ]
    ],
];