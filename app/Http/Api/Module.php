<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Api;

use App\Services\Auth\Api as ApiAuth;
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
            return new ApiAuth();
        });
    }

}
