<?php

return [
    "/scoob-eco/admin/login" => [
        "action"      => "ScoobEco\Eco\Controllers\Scoob\HomeController@login",
        "name"        => "pages.scoob.login",
        "method"      => "get",
        "middlewares" => []
    ],
    "/scoob-eco/admin/run"   => [
        "action"      => "ScoobEco\Eco\Controllers\Scoob\HomeController@loginRun",
        "name"        => "pages.scoob.login.run",
        "method"      => "post",
        "middlewares" => [
            new \ScoobEco\Eco\Middlewares\Routes\ClearFieldsMiddleware()
        ]
    ],
];