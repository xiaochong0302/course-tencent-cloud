<?php

namespace App\Providers;

use Phalcon\Db\Adapter\Pdo\Mysql as MySqlAdapter;

class Database extends AbstractProvider
{

    protected $serviceName = 'db';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {

            $config = $this->getShared('config');

            $options = [
                'host' => $config->db->host,
                'port' => $config->db->port,
                'dbname' => $config->db->dbname,
                'username' => $config->db->username,
                'password' => $config->db->password,
                'charset' => $config->db->charset,
            ];

            $connection = new MySqlAdapter($options);

            if ($config->env == ENV_DEV) {
                $connection->setEventsManager($this->getEventsManager());
            }

            return $connection;
        });
    }

}