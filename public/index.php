<?php
/** Config load */
require 'config.php';

/** Composer autoload */
require 'vendor/autoload.php';

/** Classes autoload */
spl_autoload_register(function ($classname) {
    // win-linux compatibility
    $script = str_replace('\\', '/', $classname) . '.php';
    require_once $script;
});

/** Loading .env;
 * Access through $_ENV['env_variable_name']
 **/
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, DOT_ENV_PATH);
$dotenv->load();

/** Logging
 * Errors log to file and console if dev env
 * dev log to console
 **/
use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$errorsLogHandler = new StreamHandler(ERRORS_LOG, Logger::WARNING);

$errorsLog = new Logger('errors');
$errorsLog->pushHandler($errorsLogHandler);

// if development mode
if ($_ENV['INSTANCE_TYPE'] === 'development') {
    $firePHPHandler = new FirePHPHandler();

    $devLog = new Logger('dev');

    $devLog->pushHandler($firePHPHandler);
    $errorsLog->pushHandler($firePHPHandler);
}

/** Routing initialization  */
$routeInfo = \routes\Router::create()->getRouteInfo();

switch ($routeInfo[0]) {
    // Not Fount
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo 'Not found';
        break;
    // Not Allowed
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = join($routeInfo[1]);
        http_response_code(405);
        echo "Not allowed. Use $allowedMethods";
        break;
    // Found
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        try {
            echo $handler($vars);
        } catch (Exception $e) {
            $errorsLog->error($e, ['err' => '234453']);
        }

        break;
}
