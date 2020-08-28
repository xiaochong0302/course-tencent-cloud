<?php

namespace App\Providers;

use Phalcon\Config;
use Phalcon\Db\Adapter\Pdo\Mysql as MySqlAdapter;

class Database extends Provider
{

    protected $serviceName = 'db';

    public function register()
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {

            $options = [
                'host' => $config->path('db.host'),
                'port' => $config->path('db.port'),
                'dbname' => $config->path('db.dbname'),
                'username' => $config->path('db.username'),
                'password' => $config->path('db.password'),
                'charset' => $config->path('db.charset'),
                'options' => [
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    \PDO::ATTR_STRINGIFY_FETCHES => false,
                ],
            ];

            $connection = new MySqlAdapter($options);

            if ($config->get('env') == ENV_DEV) {
                $connection->setEventsManager($this->getEventsManager());
            }

            return $connection;
        });
    }

}