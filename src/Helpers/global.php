<?php

use JetBrains\PhpStorm\NoReturn;
use ScoobEco\Core\Controllers\BaseController;
use ScoobEco\DIContainer\Resolver;
use ScoobEco\Enum\ErrorType;

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? getenv($key);

        if ($value === false || $value === null) {
            return $default;
        }

        $lower = strtolower($value);
        return match ($lower) {
            'true'  => true,
            'false' => false,
            'null'  => null,
            default => is_numeric($value) ? (float)$value == (int)$value ? (int)$value : (float)$value : $value,
        };
    }
}

if (!function_exists('dd')) {
    function dd(
        mixed $keys = null,
    ): void
    {
        http_response_code(500);
        echo "<pre style='background: black; color: white;padding: 20px;height: 100%'>";
        print_r($keys);
        echo "</pre>";
        die();
    }
}

if (!function_exists('ddError')) {
    function ddError(
        ?ErrorType $type = null,
        ?Throwable $e = null,
    ): void
    {
        if (isset($type) && isset($e)) {
            styleError($type, $e);
        }
    }

    #[NoReturn]
    function styleError(
        ErrorType $type,
        Throwable $e
    ): void
    {
        $ambient = $_ENV['SCOOB_AMBIENT'];

        if (isset($ambient) && $ambient == "dev") {
            require __DIR__ . '/../../Eco/pages-error/pages-internal/error-handler.php';
            exit;
        }

        if (isset($ambient) && $ambient == "prod") {
            require __DIR__ . '/../../Eco/pages-error/pages-internal/error-handler.php';
            exit;
        }

    }
}

if (!function_exists('asset')) {
    function asset(string $path)
    {
        return '/' . ltrim($path, '/');
    }
}


if (!function_exists('renderTemplate')) {
    function renderTemplate(string $templatePath, array $data = []): string
    {
        extract($data);

        $template = file_get_contents($templatePath);

        $template = preg_replace_callback('/\{\{\s*(.+?)\s*\}\}/', function ($matches) {
            $expression = trim($matches[1]);
            return "<?= $expression ?>";
        }, $template);

        $template = preg_replace_callback(
            '/@include\([\'"](.+?)[\'"]\)/',
            function ($matches) use ($templatePath) {
                $includePath     = "/var/www/pages" .
                    DIRECTORY_SEPARATOR .
                    str_replace(".", "/", $matches[1]) . ".blade.php";
                $templateInclude = file_get_contents($includePath);
                $templateInclude = preg_replace_callback('/\{\{\s*(.+?)\s*\}\}/', function ($matches) {
                    $expression = trim($matches[1]);
                    return "<?= $expression ?>";
                }, $templateInclude);
                return $templateInclude;
            }, $template);

        $tempPath = tempnam(sys_get_temp_dir(), 'tpl_') . '.blade.php';

        file_put_contents($tempPath, $template);

        ob_start();

        include $tempPath;

        return ob_get_clean();
    }

}

if (!function_exists('view')) {
    function view(string $viewName, array $data = []): string
    {
        return BaseController::view($viewName, $data);
    }
}

if (!function_exists('route')) {
    function route(string $routeName): string
    {
        $route = \ScoobEco\Core\Support\RouteConverter::run($routeName);
        return $route["uri"];
    }
}

if (!function_exists('isJsonRequest')) {
    function isJsonRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
}

if (!function_exists('resolveDI')) {
    /**
     * @template T
     * @param class-string<T> $class
     * @return Resolver<T>
     */
    function resolveDI(string $class): Resolver
    {
        return new Resolver($class);
    }
}


