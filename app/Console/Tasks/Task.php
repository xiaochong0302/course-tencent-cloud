<?php

namespace App\Console\Tasks;

use App\Library\Cache\Backend\Redis as RedisCache;
use App\Library\Logger as AppLogger;
use Phalcon\Config as PhConfig;
use Phalcon\Logger\Adapter\File as PhLogger;


class Task extends \Phalcon\Cli\Task
{

    /**
     * @return PhConfig
     */
    public function getConfig()
    {
        return $this->getDI()->get('config');
    }

    /**
     * @return RedisCache
     */
    public function getCache()
    {
        return $this->getDI()->get('cache');
    }

    /**
     * @param null $channel
     * @return PhLogger
     */
    public function getLogger($channel = null)
    {
        $logger = new AppLogger();

        return $logger->getInstance($channel);
    }

}
