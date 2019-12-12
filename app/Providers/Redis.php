<?php

namespace App\Providers;

class Redis extends AbstractProvider
{

    protected $serviceName = 'redis';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {

            $config = $this->getShared('config');

            $redis = new \Redis();

            $host = $config->redis->host ?: '127.0.0.1';
            $port = $config->redis->port ?: 6379;
            $persistent = $config->redis->persistent ?: false;
            $auth = $config->redis->auth ?: null;

            if ($persistent) {
                $redis->pconnect($host, $port);
            } else {
                $redis->connect($host, $port);
            }

            if ($auth) {
                $redis->auth($auth);
            }

            return $redis;
        });
    }

}