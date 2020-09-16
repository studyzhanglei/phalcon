<?php

/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */

defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

$appEnv = 'local';
if ($value = getenv('APP_ENV')) {
    $appEnv = $value;
}


return new \Phalcon\Config([
    'database'    => require APP_PATH . '/config/' . $appEnv . '/database.php',
    'aliyun'      => require APP_PATH . '/config/' . $appEnv . '/aliyun.php',
    'redis'       => require APP_PATH . '/config/' . $appEnv . '/redis.php',
    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
        'cacheDir'       => BASE_PATH . '/cache/',
        'baseUri'        => '/',
    ]
]);

