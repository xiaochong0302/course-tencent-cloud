<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace Bootstrap;

use Phalcon\Application\AbstractApplication;
use Phalcon\Config\Config;
use Phalcon\Di\Di;
use Phalcon\Autoload\Loader;

abstract class Kernel
{

    /**
     * @var Di
     */
    protected Di $di;

    /**
     * @var AbstractApplication
     */
    protected AbstractApplication $app;

    /**
     * @var Loader
     */
    protected Loader $loader;

    protected function initAppEnv(): void
    {
        require __DIR__ . '/Helper.php';
    }

    protected function registerSettings(): void
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        ini_set('date.timezone', $config->get('timezone'));

        if ($config->get('env') == ENV_DEV) {
            ini_set('display_errors', '1');
            error_reporting(E_ALL ^ E_DEPRECATED);
        } else {
            ini_set('display_errors', '0');
            error_reporting(0);
        }
    }

    abstract public function handle(): void;

    abstract protected function registerLoaders(): void;

    abstract protected function registerServices(): void;

    abstract protected function registerErrorHandler(): void;

}
