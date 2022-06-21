<?php

namespace App\Library\Session\Adapter;

use Phalcon\Session\Adapter\Redis as RedisAdapter;
use Phalcon\Cache\Frontend\None;
use App\Library\Cache\Backend\Redis as RedisBackend;

class Redis extends RedisAdapter
{
    public function __construct(array $options = [])
    {
        $this->_lifetime = $options['lifetime'];

        session_set_save_handler(
            [$this, "open"],
            [$this, "close"],
            [$this, "read"],
            [$this, "write"],
            [$this, "destroy"],
            [$this, "gc"]
        );

        parent::__construct($options);

        $this->_redis = new RedisBackend(new None(['lifetime' => $this->_lifetime]),$options);
    }
}