<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Providers;

use App\Listeners\Db as DbListener;
use Phalcon\Config as Config;
use Phalcon\Db\Adapter\Pdo\Mysql as MySqlAdapter;
use Phalcon\Events\Manager as EventsManager;

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

                $eventsManager = new EventsManager();

                $eventsManager->attach('db', new DbListener());

                $connection->setEventsManager($eventsManager);
            }

            return $connection;
        });
    }

}
