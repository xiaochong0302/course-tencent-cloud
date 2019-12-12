<?php

namespace Bootstrap;

abstract class Kernel
{

    protected $di;

    protected $app;

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
        $this->configs = require config_path() . '/config.php';
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