<?php

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../shores_website/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the AutoLoader...
require __DIR__.'/../shores_website/vendor/autoload.php';

// Bootstrap Laravel...
$app = require_once __DIR__.'/../shores_website/bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
?>
