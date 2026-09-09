<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace Bootstrap;

use App\Library\Logger as AppLogger;
use Phalcon\Logger\Logger;
use Throwable;

class ConsoleErrorHandler extends ErrorHandler
{

    public function __construct()
    {
        parent::__construct();
    }

    public function handleException(Throwable $e): void
    {
        $logger = $this->getLogger();

        $content = sprintf('%s(%d): %s', $e->getFile(), $e->getLine(), $e->getMessage());

        $logger->error($content);

        echo $content . PHP_EOL;

        $config = $this->getConfig();

        if ($config->path('env') === 'dev' || $config->path('log.trace')) {

            $trace = sprintf('Trace Content: %s', $e->getTraceAsString());

            $logger->error($trace);

            echo $trace . PHP_EOL;
        }
    }

    protected function getLogger(): Logger
    {
        $logger = new AppLogger();

        return $logger->getInstance('console');
    }

}
