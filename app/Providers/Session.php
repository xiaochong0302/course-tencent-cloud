<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use Phalcon\Config\Config;
use Phalcon\Session\Adapter\Redis as RedisAdapter;
use Phalcon\Session\Manager;
use Phalcon\Storage\AdapterFactory;
use Phalcon\Storage\SerializerFactory;

class Session extends Provider
{

    protected string $serviceName = 'session';

    public function register(): void
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {

            $session = new Manager();

            $serializerFactory = new SerializerFactory();

            $adapterFactory = new AdapterFactory($serializerFactory);

            $options = [
                'defaultSerializer' => 'redis_igbinary',
                'host' => $config->path('redis.host'),
                'port' => $config->path('redis.port'),
                'auth' => $config->path('redis.auth'),
                'index' => $config->path('redis.index'),
                'prefix' => $config->path('session.prefix'),
                'lifetime' => $config->path('session.lifetime'),
            ];

            $redisAdapter = new RedisAdapter($adapterFactory, $options);

            $session->setAdapter($redisAdapter);
            $session->start();

            return $session;
        });
    }

}
