<?php

use Bootstrap\ConsoleKernel;

require __DIR__ . '/bootstrap/Kernel.php';
require __DIR__ . '/bootstrap/ConsoleKernel.php';

$kernel = new ConsoleKernel();

$config = $kernel->getDI()->getShared('config');

$db = $config->db;

$options = [

    'version_order' => 'creation',

    'paths' => [
        'migrations' => 'db/migrations',
        'seeds' => 'db/seeds',
    ],

    'environments' => [

        'default_migration_table' => 'migration',
        'default_database' => 'production',

        'production' => [
            'adapter' => 'mysql',
            'host' => $db->host,
            'port' => $db->port,
            'name' => $db->dbname,
            'user' => $db->username,
            'pass' => $db->password,
            'charset' => $db->charset,
        ],

    ],

];

return $options;