<?php

namespace App\Providers;

use Phalcon\Config as PhalconConfig;

class Config extends AbstractProvider
{

    protected $serviceName = 'config';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {

            $options = require config_path() . '/config.php';

            $config = new PhalconConfig($options);

            return $config;
        });
    }

}