<?php
declare(strict_types=1);

use Phalcon\Di\FactoryDefault;
use Phalcon\Events\Manager as EventsManager;
use Dotenv\Dotenv;
error_reporting(E_ERROR);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {

    require_once BASE_PATH . '/vendor/autoload.php';

    /**
     * Load ENV variables
     */
    Dotenv::createImmutable(BASE_PATH)->load();

    /**
     * The FactoryDefault Dependency Injector automatically registers
     * the services that provide a full stack framework.
     */
    $di = new FactoryDefault();

    /**
     * Handle routes
     */
    include APP_PATH . '/config/web/router.php';

    /**
     * Read services
     */
    include APP_PATH . '/config/web/services.php';

    /**
     * Get config service for use in inline setup below
     */
    $config = $di->getConfig();

    /**
     * Include Autoloader
     */
    include APP_PATH . '/config/web/loader.php';



    /**
     * Handle the request
     */
    $application = new \App\Application($di);

    $eventManager = new EventsManager();
    $eventManager->attach('application', new  \App\Application);

    $application->setEventsManager($eventManager);

    set_error_handler(
        function ($errorNo, $errorStr, $errorFile, $errorLine)
        {
            throw new \Exception($errorStr);
            return;
        }
    );



    echo $application->handle($_SERVER['REQUEST_URI'])->getContent();
} catch (\Exception $e) {
    header('Content-Type: application/json');

    $data = [
        'code'  => \App\Exception\BaseException::SYSTEM_ERR,
        'msg'   => \App\Exception\BaseException::$reasons[\App\Exception\BaseException::SYSTEM_ERR],
        'data'  => [],
    ];

    if (!in_array(getenv('APP_ENV'), ['prod'])) {
        $data['msg']    = $e->getMessage();
        $data['trace']  = $e->getTraceAsString();
    }

    echo json_encode($data);
}
