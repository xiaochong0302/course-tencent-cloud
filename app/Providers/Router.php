<?php

namespace App\Providers;

use Phalcon\Mvc\Router\Annotations as AnnotationsRouter;

class Router extends AbstractProvider
{

    protected $serviceName = 'router';

    public function register()
    {
        $this->di->setShared($this->serviceName, function () {

            $router = new AnnotationsRouter(false);

            $router->removeExtraSlashes(true);

            $router->setDefaultNamespace('App\Http\Home\Controllers');

            $router->notFound([
                'module' => 'home',
                'controller' => 'error',
                'action' => 'show404',
            ]);

            $homeFiles = scandir(app_path('Http/Home/Controllers'));

            foreach ($homeFiles as $file) {
                if (strpos($file, 'Controller.php')) {
                    $className = str_replace('Controller.php', '', $file);
                    $router->addModuleResource('home', 'App\Http\Home\Controllers\\' . $className);
                }
            }

            $adminFiles = scandir(app_path('Http/Admin/Controllers'));

            foreach ($adminFiles as $file) {
                if (strpos($file, 'Controller.php')) {
                    $className = str_replace('Controller.php', '', $file);
                    $router->addModuleResource('admin', 'App\Http\Admin\Controllers\\' . $className);
                }
            }

            $apiFiles = scandir(app_path('Http/Api/Controllers'));

            foreach ($apiFiles as $file) {
                if (strpos($file, 'Controller.php')) {
                    $className = str_replace('Controller.php', '', $file);
                    $router->addModuleResource('api', 'App\Http\Api\Controllers\\' . $className);
                }
            }

            return $router;
        });
    }

}