<?php

namespace App\Http\Api;

use App\Services\Auth\Api as AppAuth;
use Phalcon\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View;

class Module implements ModuleDefinitionInterface
{

    public function registerAutoLoaders(DiInterface $dependencyInjector = null)
    {

    }

    public function registerServices(DiInterface $dependencyInjector)
    {
        $dependencyInjector->setShared('view', function () {
            $view = new View();
            $view->disable();
            return $view;
        });

        $dependencyInjector->setShared('auth', function () {
            return new AppAuth();
        });
    }

}
