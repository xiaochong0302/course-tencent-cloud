<?php

namespace App\Providers;

use App\Library\Security as AppSecurity;

class Security extends Provider
{

    protected $serviceName = 'security';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {
            return new AppSecurity();
        });
    }

}