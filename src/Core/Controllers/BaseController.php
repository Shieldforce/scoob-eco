<?php

namespace ScoobEco\Core\Controllers;

use Exception;
use ScoobEco\Core\Http\Request;

class BaseController
{
    public function __construct(public Request $request) {}

    public static function view($viewName, array $data = []): string
    {
        $viewReplace = str_replace(["."], ["/"], $viewName);
        $path        = str_replace("src/Core/Controllers", "", __DIR__);
        $file        = $path . $viewReplace . ".blade.php";

        if (!file_exists($file)) {
            throw new Exception("View $viewName not found!");
        }

        echo renderTemplate($file, $data);
        return "";
    }
}