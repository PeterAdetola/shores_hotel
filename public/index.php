<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Flexible Path Detection
|--------------------------------------------------------------------------
| This checks if the project is in a custom folder (like on DirectAdmin)
| or in the standard root folder (like on your local PC).
*/

if (is_dir(__DIR__.'/../shores_website')) {
    $laravelRoot = __DIR__.'/../shores_website';
} else {
    // Default for local development (e.g., /public/../)
    $laravelRoot = __DIR__.'/..';
}

/*
|--------------------------------------------------------------------------
| Maintenance Mode
|--------------------------------------------------------------------------
*/

if (file_exists($maintenance = $laravelRoot.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register Autoloader & Boot App
|--------------------------------------------------------------------------
*/

require $laravelRoot.'/vendor/autoload.php';

$app = require_once $laravelRoot.'/bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Environment Fix & Request Handling (Laravel 11 Style)
|--------------------------------------------------------------------------
*/

// Keep your critical fix for forced environment detection
$app->instance('env', getenv('APP_ENV') ?: $_ENV['APP_ENV'] ?? 'production');

// Handle the request using Laravel 11 handleRequest method
$app->handleRequest(Request::capture());
