<?php

namespace App\Providers;

class Cookie extends Provider
{

    protected $serviceName = 'cookies';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {

        });
    }

}