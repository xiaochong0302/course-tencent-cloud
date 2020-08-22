<?php

namespace App\Http\Desktop;

use App\Library\Mvc\View as MyView;
use App\Services\Auth\Desktop as DesktopAuth;
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
            $view->setViewsDir(__DIR__ . '/Views');
            $view->registerEngines([
                '.volt' => 'volt',
            ]);
            return $view;
        });

        $di->setShared('auth', function () {
            return new DesktopAuth();
        });
    }

}
