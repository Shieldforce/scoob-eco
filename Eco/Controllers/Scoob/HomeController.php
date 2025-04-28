<?php

namespace ScoobEco\Eco\Controllers\Scoob;

use Exception;
use ScoobEco\Core\Controllers\BaseController;
use ScoobEco\Core\Http\Request;
use ScoobEco\Core\Http\Response;
use ScoobEco\Enum\ResponseType;

class HomeController extends BaseController
{
    public function setup(Request $request)
    {
        $title = "Instalação ScoobEco";
        return view(
            'pages.scoob.setup',
            compact('title')
        );
    }

    public function setupRun(Request $request)
    {
        try {
            return Response::return(
                $this->request,
                ResponseType::success,
                "Instalação efetuada com sucesso!",
                200
            );
        } catch (Exception $exception) {
            return Response::return(
                $this->request,
                ResponseType::error,
                $exception->getMessage() ?? "Erro ao efetuar instalação!",
                500
            );
        }
    }

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
                $this->request,
                ResponseType::success,
                "Login efetuado com sucesso!",
                200
            );
        } catch (Exception $exception) {
            return Response::return(
                $this->request,
                ResponseType::error,
                $exception->getMessage() ?? "Erro ao efetuar login!",
                500
            );
        }
    }
}