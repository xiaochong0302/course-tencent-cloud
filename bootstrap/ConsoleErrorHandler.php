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
use Throwable;

class ConsoleErrorHandler extends Injectable
{

    public function __construct()
    {
        set_exception_handler([$this, 'handleException']);

        set_error_handler([$this, 'handleError']);

        register_shutdown_function([$this, 'handleShutdown']);
    }

    /**
     * @param Throwable $e
     */
    public function handleException($e)
    {
        $logger = $this->getLogger();

        $content = sprintf('%s(%d): %s', $e->getFile(), $e->getLine(), $e->getMessage());

        $logger->error($content);

        $config = $this->getConfig();

        if ($config->path('env') == 'dev' || $config->path('log.trace')) {

            $trace = sprintf('Trace Content: %s', $e->getTraceAsString());

            $logger->error($trace);

            $content .= $trace;
        }

        echo $content . PHP_EOL;
    }

    public function handleError($errNo, $errStr, $errFile, $errLine)
    {
        if (in_array($errNo, [E_WARNING, E_NOTICE, E_DEPRECATED, E_USER_WARNING, E_USER_NOTICE, E_USER_DEPRECATED])) {
            return true;
        }

        $logger = $this->getLogger();

        $logger->error("Error [{$errNo}]: {$errStr} in {$errFile} on line {$errLine}");

        return false;
    }

    public function handleShutdown()
    {
        $error = error_get_last();

        if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {

            $logger = $this->getLogger();

            $logger->error("Fatal Error [{$error['type']}]: {$error['message']} in {$error['file']} on line {$error['line']}");
        }
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
