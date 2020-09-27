<?php

namespace App\Providers;

use Phalcon\Http\Response\Cookies as PhCookies;

class Cookie extends Provider
{

    protected $serviceName = 'cookies';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {

            $cookies = new PhCookies();

            $cookies->useEncryption(true);

            return $cookies;
        });
    }

}