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
                $statsKey = '_ANNOTATION_';
                $annotations = new RedisAnnotations([
                    'host' => $config->path('redis.host'),
                    'port' => $config->path('redis.port'),
                    'auth' => $config->path('redis.auth'),
                    'lifetime' => $config->path('annotation.lifetime') ?: 30 * 86400,
                    'prefix' => $statsKey . ':',
                    'statsKey' => $statsKey,
                ]);
            }

            return $annotations;
        });
    }

}