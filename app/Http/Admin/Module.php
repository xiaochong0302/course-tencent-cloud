<?php

namespace App\Http\Admin;

use App\Services\Auth\Admin as AdminAuth;
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
            $view->setViewsDir(__DIR__ . '/Views');
            $view->registerEngines([
                '.volt' => 'volt',
            ]);
            return $view;
        });

        $di->setShared('auth', function () {
            return new AdminAuth();
        });
    }

}
