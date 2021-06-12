<?php

namespace App\Http\Home;

use App\Library\Mvc\View as MyView;
use App\Services\Auth\Home as HomeAuth;
use Phalcon\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{

    public function registerAutoLoaders(DiInterface $dependencyInjector = null)
    {

    }

    public function registerServices(DiInterface $dependencyInjector)
    {
        $dependencyInjector->setShared('view', function () {
            $view = new MyView();
            $view->setViewsDir(__DIR__ . '/Views');
            $view->registerEngines([
                '.volt' => 'volt',
            ]);
            return $view;
        });

        $dependencyInjector->setShared('auth', function () {
            return new HomeAuth();
        });
    }

}
