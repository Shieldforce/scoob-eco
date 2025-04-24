<?php

namespace ScoobEco\Controllers\Scoob;

use Exception;
use ScoobEco\Core\Controllers\BaseController;
use ScoobEco\Core\Http\Request;
use ScoobEco\Core\Http\Response;
use ScoobEco\Enum\ResponseType;

class HomeController extends BaseController
{
    public function login(Request $request)
    {
        $title = "Login ScoobEco";

        return view(
            'pages.scoob.login',
            compact('title')
        );

    }

    public function loginRun(Request $request)
    {
        try {
            return Response::return(
                $request,
                ResponseType::success,
                "Login efetuado com sucesso!",
                200
            );
        } catch (Exception $exception) {
            return Response::return(
                $request,
                ResponseType::error,
                $exception->getMessage() ?? "Erro ao efetuar login!",
                $exception->getCode() ?? 500
            );
        }
    }
}