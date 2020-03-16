<?php

$config = require __DIR__ . '/config/config.php';

$options = [

    'version_order' => 'creation',

    'paths' => [
        'migrations' => 'db/migrations',
        'seeds' => 'db/seeds',
    ],

    'environments' => [

        'default_migration_table' => 'kg_migration',

        'default_database' => 'production',

        'production' => [
            'adapter' => 'mysql',
            'host' => $config['db']['host'],
            'port' => $config['db']['port'],
            'name' => $config['db']['dbname'],
            'user' => $config['db']['username'],
            'pass' => $config['db']['password'],
            'charset' => $config['db']['charset'],
        ],

    ],

];

print_r($options);

return $options;