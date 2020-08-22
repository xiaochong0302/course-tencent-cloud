<?php

use Phalcon\Mvc\Router\Annotations as Router;

$router = new Router(false);

$router->removeExtraSlashes(true);

$router->setDefaultNamespace('App\Http\Desktop\Controllers');

$router->notFound([
    'module' => 'desktop',
    'controller' => 'error',
    'action' => 'show404',
]);

$webFiles = scandir(app_path('Http/Desktop/Controllers'));

foreach ($webFiles as $file) {
    if (strpos($file, 'Controller.php')) {
        $className = str_replace('Controller.php', '', $file);
        $router->addModuleResource('desktop', 'App\Http\Desktop\Controllers\\' . $className);
    }
}

$mobileFiles = scandir(app_path('Http/Mobile/Controllers'));

foreach ($mobileFiles as $file) {
    if (strpos($file, 'Controller.php')) {
        $className = str_replace('Controller.php', '', $file);
        $router->addModuleResource('mobile', 'App\Http\Mobile\Controllers\\' . $className);
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