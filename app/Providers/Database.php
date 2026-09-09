<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use App\Listeners\Db as DbListener;
use Phalcon\Config\Config as Config;
use Phalcon\Db\Adapter\Pdo\Mysql as MySqlAdapter;
use Phalcon\Events\Manager as EventsManager;

class Database extends Provider
{

    protected string $serviceName = 'db';

    public function register(): void
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
                    \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                ],
            ];

            $connection = new MySqlAdapter($options);

            if ($config->get('env') == ENV_DEV) {

                $eventsManager = new EventsManager();

                $eventsManager->attach('db', new DbListener());

                $connection->setEventsManager($eventsManager);
            }

            return $connection;
        });
    }

}
