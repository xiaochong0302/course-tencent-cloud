<?php

namespace App\Providers;

use Phalcon\Crypt as PhalconCrypt;

class Crypt extends AbstractProvider
{

    protected $serviceName = 'crypt';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {

            $config = $this->getShared('config');

            $crypt = new PhalconCrypt();

            $crypt->setKey($config->key);

            return $crypt;
        });
    }

}