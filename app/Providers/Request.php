<?php

namespace App\Providers;

use App\Library\Http\Request as MyRequest;

class Request extends Provider
{

    protected $serviceName = 'request';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {
            return new MyRequest();
        });
    }

}