<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Library;

use Phalcon\Config\Config;
use Phalcon\Di\Di;
use Phalcon\Logger\AbstractLogger;
use Phalcon\Logger\Adapter\Stream as StreamAdapter;
use Phalcon\Logger\AdapterFactory;
use Phalcon\Logger\Logger as PhLogger;
use Phalcon\Logger\LoggerFactory;

class Logger
{

    /**
     * @var array
     */
    private static array $instances = [];

    /**
     * @var array
     */
    private static array $instanceDates = [];

    public function getInstance(string $channel = 'common'): PhLogger
    {
        $today = date('Y-m-d');

        if (isset(self::$instances[$channel]) &&
            isset(self::$instanceDates[$channel]) &&
            self::$instanceDates[$channel] == $today) {
            return self::$instances[$channel];
        }

        /**
         * @var Config $config
         */
        $config = Di::getDefault()->getShared('config');

        $filename = sprintf('%s-%s.log', $channel, $today);

        $path = log_path($filename);

        $level = $config->get('env') != ENV_DEV ? $config->path('log.level') : AbstractLogger::DEBUG;

        $adapters = [
            'main' => new StreamAdapter($path),
        ];

        $adapterFactory = new AdapterFactory();

        $loggerFactory = new LoggerFactory($adapterFactory);

        $logger = $loggerFactory->newInstance('prod-logger', $adapters);

        $logger->setLogLevel($level);

        self::$instances[$channel] = $logger;
        self::$instanceDates[$channel] = $today;

        return $logger;
    }

}
