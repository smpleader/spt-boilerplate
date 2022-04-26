<?php  defined('APP_PATH') or die('Invalid config');

return [ 
    'sitepath' => '',
    'plugins' => ['core'],
    'theme' => 'demo',
    'secrect' => 'sid',
    'endpoints' => [
    ],
    'defaultEndpoint' => [
        '' => 'core.home.demo'
    ],
    'db' => [
        'host' => 'mysql',
        'username' => 'username',
        'passwd' => 'password',
        'database' => 'database-name',
        'prefix' => 'tbl_',
        'debug' => true
    ],
];