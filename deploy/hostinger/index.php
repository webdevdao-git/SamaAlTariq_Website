<?php

/**
 * Front controller for Hostinger shared hosting.
 *
 * Copy this file into `public_html/` when the document root cannot be pointed
 * at Laravel's `public/` directory — which is the case on Hostinger's shared
 * plans, where every site is served from a fixed `public_html`.
 *
 * The layout it assumes:
 *
 *   ~/domains/<your-domain>/
 *       app/            ← the repository (this project)
 *       public_html/    ← this file + everything from app/public/
 *
 * Only $APP_BASE below needs changing if your folder is named differently.
 * Nothing in the application itself is modified, so `git pull` never conflicts.
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/**
 * Path from public_html to the Laravel project root.
 * `..` steps up to ~/domains/<your-domain>, then into `app`.
 */
$APP_BASE = __DIR__.'/../app';

if (! is_file($APP_BASE.'/vendor/autoload.php')) {
    http_response_code(500);
    exit('Laravel not found at '.$APP_BASE.' — check $APP_BASE in public_html/index.php');
}

// Maintenance mode...
if (file_exists($maintenance = $APP_BASE.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Composer autoloader...
require $APP_BASE.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once $APP_BASE.'/bootstrap/app.php';

/*
 * Tell Laravel that its public directory is public_html, not app/public.
 * Without this, asset() and the Vite helper generate URLs against the wrong
 * path and every stylesheet and script 404s.
 */
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
