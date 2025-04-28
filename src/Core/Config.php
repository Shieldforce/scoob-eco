<?php

namespace ScoobEco\Core;

class Config
{
    protected static array $config = [];

    public static function load(string $path = __DIR__ . '/../../Eco/config')
    {
        foreach (glob($path . '/*.php') as $file) {
            $name                  = basename($file, '.php');
            static::$config[$name] = require $file;
        }
    }

    public static function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $value = static::$config;

        foreach ($keys as $segment) {
            if (is_array($value) && array_key_exists($segment, $value)) {
                $value = $value[$segment];
            } else {
                return $default;
            }
        }

        return $value;
    }
}