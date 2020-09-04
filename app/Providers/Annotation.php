<?php

namespace App\Providers;

use Phalcon\Annotations\Adapter\Memory as MemoryAnnotations;
use Phalcon\Annotations\Adapter\Redis as RedisAnnotations;
use Phalcon\Config;

class Annotation extends Provider
{

    protected $serviceName = 'annotations';

    public function register()
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {

            if ($config->get('env') == ENV_DEV) {
                $annotations = new MemoryAnnotations();
            } else {
                $annotations = new RedisAnnotations([
                    'host' => $config->path('redis.host'),
                    'port' => $config->path('redis.port'),
                    'auth' => $config->path('redis.auth'),
                    'index' => $config->path('annotation.db'),
                    'lifetime' => $config->path('annotation.lifetime'),
                    'statsKey' => $config->path('annotation.statsKey'),
                ]);
            }

            return $annotations;
        });
    }

}