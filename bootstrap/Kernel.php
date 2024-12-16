<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace Bootstrap;

use Phalcon\Application;
use Phalcon\Config;
use Phalcon\Di;
use Phalcon\Loader;

abstract class Kernel
{

    /**
     * @var Di
     */
    protected $di;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Loader
     */
    protected $loader;

    protected function initAppEnv()
    {
        require __DIR__ . '/Helper.php';
    }

    protected function registerSettings()
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        ini_set('date.timezone', $config->get('timezone'));

        if ($config->get('env') == ENV_DEV) {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            error_reporting(0);
        }
    }

    abstract public function handle();

    abstract protected function registerLoaders();

    abstract protected function registerServices();

    abstract protected function registerErrorHandler();

}
