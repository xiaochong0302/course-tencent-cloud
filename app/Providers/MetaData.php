<?php

namespace App\Providers;

use Phalcon\Config;
use Phalcon\Mvc\Model\MetaData\Memory as MemoryMetaData;
use Phalcon\Mvc\Model\MetaData\Redis as RedisMetaData;

class MetaData extends Provider
{

    protected $serviceName = 'modelsMetadata';

    public function register()
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {

            if ($config->get('env') == ENV_DEV) {
                $metaData = new MemoryMetaData();
            } else {
                $metaData = new RedisMetaData([
                    'host' => $config->path('redis.host'),
                    'port' => $config->path('redis.port'),
                    'auth' => $config->path('redis.auth'),
                    'index' => $config->path('metadata.db'),
                    'statsKey' => $config->path('metadata.statsKey'),
                    'lifetime' => $config->path('metadata.lifetime'),
                ]);
            }

            return $metaData;
        });
    }

}