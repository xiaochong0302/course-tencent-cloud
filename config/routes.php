<?php

use Phalcon\Mvc\Router\Annotations as Router;

$router = new Router(false);

$router->removeExtraSlashes(true);

$router->setDefaultNamespace('App\Http\Web\Controllers');

$router->notFound([
    'module' => 'web',
    'controller' => 'error',
    'action' => 'show404',
]);

$webFiles = scandir(app_path('Http/Web/Controllers'));

foreach ($webFiles as $file) {
    if (strpos($file, 'Controller.php')) {
        $className = str_replace('Controller.php', '', $file);
        $router->addModuleResource('web', 'App\Http\Web\Controllers\\' . $className);
    }
}

$html5Files = scandir(app_path('Http/Html5/Controllers'));

foreach ($html5Files as $file) {
    if (strpos($file, 'Controller.php')) {
        $className = str_replace('Controller.php', '', $file);
        $router->addModuleResource('html5', 'App\Http\Html5\Controllers\\' . $className);
    }
}

$apiFiles = scandir(app_path('Http/Api/Controllers'));

foreach ($apiFiles as $file) {
    if (strpos($file, 'Controller.php')) {
        $className = str_replace('Controller.php', '', $file);
        $router->addModuleResource('api', 'App\Http\Api\Controllers\\' . $className);
    }
}

$adminFiles = scandir(app_path('Http/Admin/Controllers'));

foreach ($adminFiles as $file) {
    if (strpos($file, 'Controller.php')) {
        $className = str_replace('Controller.php', '', $file);
        $router->addModuleResource('admin', 'App\Http\Admin\Controllers\\' . $className);
    }
}

return $router;