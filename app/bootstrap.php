<?php
declare(strict_types=1);

use Phalcon\Di\FactoryDefault\Cli as CliDi;
use Phalcon\Cli\Console as ConsoleApp;
use Dotenv\Dotenv;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

/**
 * The FactoryDefault Dependency Injector automatically registers the services that
 * provide a full stack framework. These default services can be overidden with custom ones.
 */
$di = new CliDi();

require_once BASE_PATH . '/vendor/autoload.php';

/**
 * Load ENV variables
 */
Dotenv::createImmutable(BASE_PATH)->load();

/**
 * Include Services
 */
include APP_PATH . '/config/cli/services.php';

/**
 * Get config service for use in inline setup below
 */
$config = $di->getConfig();

/**
 * Include Autoloader
 */
include APP_PATH . '/config/cli/loader.php';

/**
 * Handle routes
 */
include APP_PATH . '/config/cli/router.php';


/**
 * Create a console application
 */
$console = new ConsoleApp($di);

/**
 * Register console modules
 */
$console->registerModules([
    'cli' => ['className' => 'App\\Tasks\\Module']
]);

/**
 * Setup the arguments to use the 'cli' module
 */
$arguments = ['module' => 'cli'];


foreach ($argv as $k => $arg) {
    if ($k == 1) {
        $arguments['task'] = $arg;
    } elseif ($k == 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

try {

    set_error_handler(
        function ($errorNo, $errorStr, $errorFile, $errorLine)
        {
            throw new \Exception($errorStr);
            return;
        }
    );

    /**
     * Handle
     */
    $console->handle($arguments);

    /**
     * If configs is set to true, then we print a new line at the end of each execution
     *
     * If we dont print a new line,
     * then the next command prompt will be placed directly on the left of the output
     * and it is less readable.
     *
     * You can disable this behaviour if the output of your application needs to don't have a new line at end
     */
    if (isset($config["printNewLine"]) && $config["printNewLine"]) {
        echo PHP_EOL;
    }

} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
    exit(255);
}
