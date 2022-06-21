<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phalcon\Mvc\Router\Annotations as Router;

$router = new Router(false);

$router->removeExtraSlashes(true);

$router->setDefaultNamespace('App\Http\Home\Controllers');

$router->notFound([
    'module' => 'home',
    'controller' => 'error',
    'action' => 'show404',
]);

$modules = ['home','api','admin'];

foreach ($modules as $module) {
    $webFiles = scandir(app_path('Http/'.ucfirst($module).'/Controllers'));
    foreach ($webFiles as $file) {
        if (strpos($file, 'Controller.php')) {
            $className = str_replace('Controller.php', '', $file);
            $router->addModuleResource($module, 'App\Http\\'.ucfirst($module).'\Controllers\\' . $className);
        }
    }
}

return $router;