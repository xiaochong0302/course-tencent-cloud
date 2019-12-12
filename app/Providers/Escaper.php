<?php

namespace App\Providers;

use Phalcon\Escaper as PhalconEscaper;

class Escaper extends AbstractProvider
{

    protected $serviceName = 'escaper';

    public function register()
    {
        $this->di->setShared($this->serviceName, PhalconEscaper::class);
    }

}