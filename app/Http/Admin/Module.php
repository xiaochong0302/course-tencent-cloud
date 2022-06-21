<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin;

use App\Library\Mvc\View as MyView;
use App\Services\Auth\Admin as AdminAuth;
use Phalcon\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;
use App\Providers\Acl;

class Module implements ModuleDefinitionInterface
{

    public function registerAutoLoaders(DiInterface $dependencyInjector = null)
    {

    }

    public function registerServices(DiInterface $dependencyInjector)
    {
        /*$dependencyInjector->setShared('acl', function () use($dependencyInjector) {
            $acl = new Acl($dependencyInjector);
            $acl->register();
            return $acl;
        });*/

        $dependencyInjector->get('view')->setViewsDir(__DIR__ . '/Views');

        $dependencyInjector->setShared('auth', function () {
            return new AdminAuth();
        });
    }

}
