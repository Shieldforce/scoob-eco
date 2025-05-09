<?php

return [
    "connections" => [
        "mysql" => [
            'driver'     => env('DB_DRIVER', 'mysql'),
            'connection' => env('DB_CONNECTION', 'mysql'),
            "host"       => env('DB_HOST', 'scoob-mysql'),
            "port"       => env('DB_PORT', '3399'),
            "user"       => env('DB_USER', 'scoob_user'),
            "pass"       => env('DB_PASS', 'scoob_pass'),
            "db"         => env('DB_NAME', 'scoob_db'),
            "charset"    => env('DB_CHARSET', 'utf8mb4'),
        ],
        "redis" => [
            "host"       => env('REDIS_HOST', '127.0.0.1'),
            "port"       => env('REDIS_PORT', '6379'),
            "pass"       => env('REDIS_PASS', null),
        ]
    ],
];