<?php

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

    protected $configs = [];

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

    protected function initAppConfigs()
    {
        $this->configs = require config_path('config.php');
    }

    protected function initAppSettings()
    {
        ini_set('date.timezone', $this->configs['timezone']);

        if ($this->configs['env'] == ENV_DEV) {
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