<?php

namespace App\Providers;

use Phalcon\Cli\Dispatcher;

class CliDispatcher extends AbstractProvider
{

    protected $serviceName = 'dispatcher';

    public function register()
    {
        $this->di->setShared($this->serviceName, function() {

            $dispatcher = new Dispatcher();

            $dispatcher->setDefaultNamespace('App\Console\Tasks');

            return $dispatcher;
        });
    }

}