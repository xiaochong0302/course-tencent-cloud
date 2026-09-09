<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use Phalcon\Cache\AdapterFactory;
use Phalcon\Cache\CacheFactory;
use Phalcon\Config\Config;
use Phalcon\Storage\SerializerFactory;

class Cache extends Provider
{

    protected string $serviceName = 'cache';

    public function register(): void
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {

            $serializerFactory = new SerializerFactory();
            $adapterFactory = new AdapterFactory($serializerFactory);
            $cacheFactory = new CacheFactory($adapterFactory);

            $options = [
                'defaultSerializer' => 'redis_igbinary',
                'host' => $config->path('redis.host'),
                'port' => $config->path('redis.port'),
                'auth' => $config->path('redis.auth'),
                'index' => $config->path('redis.index'),
            ];

            return $cacheFactory->newInstance('redis', $options);
        });
    }

}
