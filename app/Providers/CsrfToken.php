<?php

namespace App\Providers;

use App\Library\CsrfToken as MyCsrfToken;

class CsrfToken extends Provider
{

    protected $serviceName = 'csrfToken';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {
            return new MyCsrfToken();
        });
    }

}