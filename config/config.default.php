<?php

$config = [];

/**
 * 运行环境（dev|test|pro）
 */
$config['env'] = 'pro';

$config['key'] = '223B08C66B0EC20466F513C4D9F8115D';

$config['timezone'] = 'Asia/Shanghai';

$config['url'] = [
    'base' => '/', // 必须以"/"结尾
    'static' => '/static/', // 必须以"/"结尾
];

$config['db'] = [
    'adapter' => 'Mysql',
    'host' => 'localhost',
    'username' => '',
    'password' => '',
    'dbname' => '',
    'charset' => 'utf8',
];

$config['redis'] = [
    'host' => '127.0.0.1',
    'port' => 6379,
    'persistent' => false,
    'auth' => '',
    'index' => 0,
    'lifetime' => 86400,
];

$config['session'] = [
    'lifetime' => 7200,
];

$config['log'] = [
    'level' => Phalcon\Logger::INFO,
];

return $config;
