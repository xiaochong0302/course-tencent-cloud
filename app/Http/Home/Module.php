<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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

    public function registerServices(DiInterface $di)
    {
        /*$dependencyInjector->setShared('view', function () {
            $view = new MyView();
            $view->setViewsDir(__DIR__ . '/Views');
            $view->registerEngines([
                '.volt' => 'volt',
            ]);
            return $view;
        });*/
        $di->get('view')->setViewsDir(__DIR__ . '/Views');

        $di->setShared('auth', function () {
            return new HomeAuth();
        });
    }

}
