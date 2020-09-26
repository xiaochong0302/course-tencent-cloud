<?php

namespace App\Providers;

use Phalcon\Escaper as PhEscaper;

class Escaper extends Provider
{

    protected $serviceName = 'escaper';

    public function register()
    {
        $this->di->setShared($this->serviceName, PhEscaper::class);
    }

}