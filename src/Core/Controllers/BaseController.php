<?php

namespace ScoobEco\Core\Controllers;

class BaseController
{

    public static function view($viewName, array $data = []): string
    {
        $viewReplace = str_replace(["."], ["/"], $viewName);
        $path        = str_replace("src/Core/Controllers", "", __DIR__);
        $file        = $path . $viewReplace . ".blade.php";
        echo renderTemplate($file, $data);
        return "";
    }
}