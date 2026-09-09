<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use Phalcon\Cache\AdapterFactory;
use Phalcon\Config\Config;
use Phalcon\Mvc\Model\MetaData\Memory as MemoryMetaData;
use Phalcon\Mvc\Model\MetaData\Redis as RedisMetaData;
use Phalcon\Storage\SerializerFactory;

class MetaData extends Provider
{

    protected string $serviceName = 'modelsMetadata';

    public function register(): void
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {

            if ($config->get('env') == ENV_DEV) {

                $metaData = new MemoryMetaData();

            } else {

                $serializerFactory = new SerializerFactory();

                $adapterFactory = new AdapterFactory($serializerFactory);

                $options = [
                    'defaultSerializer' => 'redis_igbinary',
                    'host' => $config->path('redis.host'),
                    'port' => $config->path('redis.port'),
                    'auth' => $config->path('redis.auth'),
                    'index' => $config->path('redis.index'),
                    'prefix' => $config->path('metadata.prefix'),
                    'lifetime' => $config->path('metadata.lifetime'),
                ];

                $metaData = new RedisMetaData($adapterFactory, $options);
            }

            return $metaData;
        });
    }

}
