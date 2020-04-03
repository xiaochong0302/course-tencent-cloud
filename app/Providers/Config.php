<?php

namespace App\Providers;

use Phalcon\Config as PhalconConfig;

class Config extends Provider
{

    protected $serviceName = 'config';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {

            $options = require config_path() . '/config.php';

            return new PhalconConfig($options);
        });
    }

}