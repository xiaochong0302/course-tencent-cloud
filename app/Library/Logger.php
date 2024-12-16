<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Library;

use Phalcon\Config;
use Phalcon\Di;
use Phalcon\Logger as PhLogger;
use Phalcon\Logger\Adapter\File as FileLogger;

class Logger
{

    /**
     * @param string $channel
     * @return FileLogger
     */
    public function getInstance($channel = 'common')
    {
        /**
         * @var Config $config
         */
        $config = Di::getDefault()->getShared('config');

        $filename = sprintf('%s-%s.log', $channel, date('Y-m-d'));

        $path = log_path($filename);

        $level = $config->get('env') != ENV_DEV ? $config->path('log.level') : PhLogger::DEBUG;

        $logger = new FileLogger($path);

        $logger->setLogLevel($level);

        return $logger;
    }

}
