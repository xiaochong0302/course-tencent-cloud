<?php

namespace App\Http\Mobile;

use App\Library\Mvc\View as MyView;
use App\Services\Auth\Mobile as MobileAuth;
use Phalcon\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{

    public function registerAutoLoaders(DiInterface $di = null)
    {

    }

    public function registerServices(DiInterface $di)
    {
        $di->setShared('view', function () {
            $view = new MyView();
            $view->disable();
            return $view;
        });

        $di->setShared('auth', function () {
            return new MobileAuth();
        });
    }

}
