<?php

use ScoobEco\InitSystem\Boot;

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

ini_set('display_errors', 0);
error_reporting(E_ALL);

new Boot();