<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
try {
    $app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());

} catch (\Throwable $th) {
    if (str_contains($e->getMessage(), 'SQLSTATE[HY000] [2002]')) {
        http_response_code(503);
        echo '<h1>⚙️ La base de datos está arrancando...</h1>';
        echo '<p>Por favor, intenta nuevamente en unos segundos.</p>';
        exit;
    }

    throw $e;
}
