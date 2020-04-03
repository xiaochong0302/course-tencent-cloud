<?php

namespace App\Http\Api;

use App\Services\AuthUser\Web as ApiAuthUser;
use Phalcon\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View;

class Module implements ModuleDefinitionInterface
{

    public function registerAutoLoaders(DiInterface $di = null)
    {

    }

    public function registerServices(DiInterface $di)
    {
        $di->setShared('view', function () {
            $view = new View();
            $view->disable();
            return $view;
        });

        $di->setShared('auth', function () {
            return new ApiAuthUser();
        });
    }

}
