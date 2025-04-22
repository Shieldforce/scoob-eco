<?php

use JetBrains\PhpStorm\NoReturn;
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
        echo "<pre style='background: black; color: white;padding: 20px;'>";
        print_r($keys);
        echo "</pre>";
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
        $ambient = $_ENV['DB_AMBIENT'];

        if (isset($ambient) && $ambient == "dev") {
            require __DIR__ . '/../../frontend/pages-internal/error-handler.php';
            exit;
        }

        if (isset($ambient) && $ambient == "prod") {
            require __DIR__ . '/../../frontend/pages-internal/error-handler.php';
            exit;
        }

    }
}