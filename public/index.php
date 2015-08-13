<?php

define('DEBUG', false);
define('DEBUGAUTOLOAD', false);
define('domain', 'localhost');

define('BASE', __DIR__ . "\\..\\");
    define('CONFIG', BASE . "config\\");
    define('FRAMEWORK', BASE . "framework\\");
        define('EXCEPTIONS', FRAMEWORK . "exceptions\\");
    define('CONTROLLERS', BASE . "controllers\\");
    define('MODELS', BASE . "models\\");
    define('VIEWS', BASE . "views\\");
    define('STYLES', BASE . "public\\styles\\");
    define('SCRIPTS', BASE . "public\\scripts\\");
    define('VENDOR', BASE . "vendor\\");
    
ob_start();

require VENDOR.'autoload.php';

if (DEBUGAUTOLOAD) {
    spl_autoload_register(function($class) {
        echo("== " . $class . " ==<br />");
        $class = strtolower(str_replace('\\', DIRECTORY_SEPARATOR, ltrim($class, '\\')));

        echo("Trying " . FRAMEWORK . $class . ".php");
        if (file_exists(FRAMEWORK . $class . ".php")) {
            echo(": Success<br /><br />");
            include(FRAMEWORK . $class . ".php");
            return;
        }

        echo(": Failure<br />Trying " . EXCEPTIONS . $class . ".php");
        if (file_exists(EXCEPTIONS . $class . ".php")) {
            echo(": Success<br /><br />");
            include(EXCEPTIONS . $class . ".php");
            return;
        }

        echo(": Failure<br />Trying " . BASE . $class . ".php");
        if (file_exists(BASE . $class . ".php")) {
            echo(": Success<br /><br />");
            include(BASE . $class . ".php");
            return;
        }

        echo(": Failure<br /><br />");
        throw new FileNotFoundException($class . ".php");
    });
} else {
    spl_autoload_register(function($class) {
        $class = strtolower(str_replace('\\', DIRECTORY_SEPARATOR, ltrim($class, '\\')));

        if (file_exists(FRAMEWORK . $class . ".php"))
            include(FRAMEWORK . $class . ".php");
        elseif (file_exists(EXCEPTIONS . $class . ".php"))
            include(EXCEPTIONS . $class . ".php");
        elseif (file_exists(BASE . $class . ".php"))
            include(BASE . $class . ".php");
        else
            throw new FileNotFoundException($class . ".php");
    });
}

function cleanup() {
    $err = error_get_last();
    if ($err !== null && $err["type"] == E_ERROR) {
        ob_end_clean();
        $response = new ErrorResponse();
        $response->render();
    }
}

if (!DEBUG)
    register_shutdown_function("cleanup");

use Routing\Router as Router;

try {
    header('Content-Type: text/html; charset=utf-8');
    $router = new Router();
    $router->run();
} catch (Exception $e) {
    cleanup();
}