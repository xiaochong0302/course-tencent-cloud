<?php

namespace App\Providers;

use Phalcon\Annotations\Adapter\Memory as MemoryAnnotations;
use Phalcon\Annotations\Adapter\Redis as RedisAnnotations;

class Annotation extends Provider
{

    protected $serviceName = 'annotations';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {

            $config = $this->getShared('config');

            if ($config->env == ENV_DEV) {
                $annotations = new MemoryAnnotations();
            } else {
                $annotations = new RedisAnnotations([
                    'host' => $config->redis->host,
                    'port' => $config->redis->port,
                    'auth' => $config->redis->auth,
                    'index' => $config->annotation->db,
                    'lifetime' => $config->annotation->lifetime,
                    'statsKey' => $config->annotation->statsKey,
                ]);
            }

            return $annotations;
        });
    }

}