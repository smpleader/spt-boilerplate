<?php  defined('APP_PATH') or die('Invalid config');

return [ 
    'sitepath' => '',
    'plugins' => ['restapi'],
    'secrect' => 'sid',
    'endpoints' => [
    ],
    'defaultEndpoint' => [
        'fnc' => 'restapi.home.get'
    ],
    DB_CONFIG,
    'db' => [
        'host' => 'DB_HOST',
        'username' => 'DB_USER',
        'passwd' => 'DB_PASS',
        'database' => 'DB_NAME',
        'prefix' => 'DB_PREFIX',
        'debug' => true
    ],
    DB_CONFIG_END,
];
