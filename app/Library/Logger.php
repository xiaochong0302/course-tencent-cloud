<?php

namespace App\Library;

use Phalcon\Di;
use Phalcon\Logger as PhalconLogger;
use Phalcon\Logger\Adapter\File as FileLogger;

class Logger
{

    /**
     * @param string $channel
     * @return FileLogger
     */
    public function getInstance($channel = null)
    {
        $config = Di::getDefault()->get('config');

        $channel = $channel ? $channel . '-' : '';

        $path = log_path() . '/' . $channel . date('Y-m-d') . '.log';

        $level = $config->env != ENV_DEV ? $config->log->level : PhalconLogger::DEBUG;

        $logger = new FileLogger($path);

        $logger->setLogLevel($level);

        return $logger;
    }

}
