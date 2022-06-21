<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace Bootstrap;

use Phalcon\Application;
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

    /**
     * @var array
     */
    protected $config = [];

    public function getApp()
    {
        return $this->app;
    }

    public function getDI()
    {
        return $this->di;
    }

    protected function initAppEnv()
    {
        require __DIR__ . '/Helper.php';
    }

    protected function initAppConfig()
    {
        $this->config = require config_path('config.php');
    }

    protected function initAppSetting()
    {
        ini_set('date.timezone', $this->config['timezone']);
        if ($this->config['env'] == ENV_DEV) {
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
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
