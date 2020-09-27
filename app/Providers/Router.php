<?php

namespace App\Providers;

class Router extends Provider
{

    protected $serviceName = 'router';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {
            return require config_path('routes.php');
        });
    }

}