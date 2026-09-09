<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace Bootstrap;

use Phalcon\Config\Config;
use Phalcon\Di\Injectable;
use Phalcon\Logger\Logger;
use Throwable;

abstract class ErrorHandler extends Injectable
{

    public function __construct()
    {
        set_exception_handler([$this, 'handleException']);

        set_error_handler([$this, 'handleError']);

        register_shutdown_function([$this, 'handleShutdown']);
    }

    public function handleError(int $errNo, string $errMsg, string $errFile, int $errLine): bool
    {
        $errorTypes = [
            E_WARNING,
            E_NOTICE,
            E_DEPRECATED,
            E_USER_WARNING,
            E_USER_NOTICE,
            E_USER_DEPRECATED,
        ];

        if (in_array($errNo, $errorTypes)) {
            return true;
        }

        $logger = $this->getLogger();

        $logger->error("Error [{$errNo}]: {$errMsg} in {$errFile} on line {$errLine}");

        return false;
    }

    public function handleShutdown(): void
    {
        $error = error_get_last();

        $errorTypes = [
            E_ERROR,
            E_PARSE,
            E_CORE_ERROR,
            E_COMPILE_ERROR,
        ];

        if ($error !== null && in_array($error['type'], $errorTypes)) {

            $logger = $this->getLogger();

            $logger->error("Fatal Error [{$error['type']}]: {$error['message']} in {$error['file']} on line {$error['line']}");
        }
    }

    protected function getConfig(): Config
    {
        return $this->getDI()->getShared('config');
    }

    abstract public function handleException(Throwable $e): void;

    abstract protected function getLogger(): Logger;

}
