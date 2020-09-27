<?php

namespace App\Providers;

use App\Library\Http\Response as MyResponse;

class Response extends Provider
{

    protected $serviceName = 'response';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {
            return new MyResponse();
        });
    }

}