<?php

namespace App\Providers;

use App\Library\Mvc\View as MyView;

class View extends Provider
{

    protected $serviceName = 'view';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {
            return new MyView();
        });
    }

}