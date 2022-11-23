<?php  defined('APP_PATH') or die('Invalid config');

return [ 
    'sitepath' => '',
    'plugins' => ['restapi'],
    'secrect' => 'sid',
    'endpoints' => [
    ],
    'defaultEndpoint' => [
        'fnc' => 'restapi.home.display'
    ],
    DB_CONFIG,
    'db' => [
        'host' => 'DB_HOST',
        'username' => 'DB_USER',
        'password' => 'DB_PASS',
        'database' => 'DB_NAME',
        'prefix' => 'DB_PREFIX',
        'debug' => true
    ],
    DB_CONFIG_END,
];
