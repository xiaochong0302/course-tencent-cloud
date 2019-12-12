<?php

namespace App\Providers;

use App\Library\Security as AppSecurity;

class Security extends AbstractProvider
{

    protected $serviceName = 'security';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {

            $security = new AppSecurity();

            return $security;
        });
    }

}