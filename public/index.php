<?php

require __DIR__ . '/../vendor/autoload.php';

use ScoobEco\InitSystem\Boot;

ini_set('display_errors', 0);
error_reporting(E_ALL);

/* Start system ScoobEco */
new Boot();