<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace Bootstrap;

use App\Library\Logger as AppLogger;
use Phalcon\Config as PhConfig;
use Phalcon\Di\Injectable;
use Phalcon\Logger\Adapter\File as PhLogger;

class ConsoleErrorHandler extends Injectable
{

    public function __construct()
    {
        set_exception_handler([$this, 'handleException']);
    }

    /**
     * @param \Throwable $e
     */
    public function handleException($e)
    {
        $logger = $this->getLogger();

        $content = sprintf('%s(%d): %s', $e->getFile(), $e->getLine(), $e->getMessage());

        $logger->error($content);

        $config = $this->getConfig();

        if ($config->get('env') == 'dev') {
            $trace = sprintf('%sTrace Content: %s', PHP_EOL, $e->getTraceAsString());
            $logger->error($trace);
            $content .= $trace;
        }

        echo $content . PHP_EOL;
    }

    /**
     * @return PhConfig
     */
    protected function getConfig()
    {
        return $this->getDI()->getShared('config');
    }

    /**
     * @return PhLogger
     */
    protected function getLogger()
    {
        $logger = new AppLogger();

        return $logger->getInstance('console');
    }

}
