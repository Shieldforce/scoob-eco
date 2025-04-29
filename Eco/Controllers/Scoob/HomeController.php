<?php

namespace ScoobEco\Eco\Controllers\Scoob;

use Exception;
use ScoobEco\Core\Controllers\BaseController;
use ScoobEco\Core\Database\DB;
use ScoobEco\Core\Http\Request;
use ScoobEco\Core\Http\Response;
use ScoobEco\Eco\Enum\SetupScoobType;
use ScoobEco\Eco\Services\Scoob\StepInstallationScoobService;
use ScoobEco\Eco\Services\Session\SessionManager;
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

    public function setupRun(
        Request $request,
        int     $typeStep
    )
    {
        try {
            $typeStepClass = SetupScoobType::tryFrom($typeStep);
            $siss = new StepInstallationScoobService($request, $typeStepClass);
            $typeStep = $siss->run();

            return Response::return(
                $request,
                ResponseType::success,
                $typeStep->message(),
                200
            );
        } catch (Exception $exception) {
            return Response::return(
                $request,
                ResponseType::error,
                $exception->getMessage() ?? "Erro ao criar tabelas!",
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
            $session = new SessionManager();
            $user = DB::prepare()->table("scoob_users")->find(1);
            $session->createSession($user, "user_session");

            //$sessions = $session->clearAllSessions();
            //$sessions = $session->listSessions();
            $sessions = $session->getSession("user_session");

            dd($sessions);

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
                500
            );
        }
    }
}