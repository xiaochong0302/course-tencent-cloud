<?php

namespace App\Providers;

use Phalcon\Config;
use Phalcon\Session\Adapter\Redis as RedisSession;

class Session extends Provider
{

    protected $serviceName = 'session';

    public function register()
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {

            $session = new RedisSession([
                'host' => $config->path('redis.host'),
                'port' => $config->path('redis.port'),
                'auth' => $config->path('redis.auth'),
                'lifetime' => $config->path('session.lifetime') ?: 24 * 3600,
                'prefix' => '_SESSION_:',
            ]);

            $session->start();

            return $session;
        });
    }

}