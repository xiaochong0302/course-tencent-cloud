<?php

namespace App\Providers;

use Phalcon\Config;
use Phalcon\Crypt as PhCrypt;

class Crypt extends Provider
{

    protected $serviceName = 'crypt';

    public function register()
    {
        /**
         * @var Config $config
         */
        $config = $this->di->getShared('config');

        $this->di->setShared($this->serviceName, function () use ($config) {

            $crypt = new PhCrypt();

            $crypt->setKey($config->get('key'));

            return $crypt;
        });
    }

}