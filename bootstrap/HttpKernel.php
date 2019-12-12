<?php

namespace Bootstrap;

class HttpKernel extends Kernel
{

    public function __construct()
    {
        $this->di = new \Phalcon\Di\FactoryDefault();
        $this->app = new \Phalcon\Mvc\Application();
        $this->loader = new \Phalcon\Loader();

        $this->initAppEnv();
        $this->initAppConfigs();
        $this->initAppSettings();
        $this->registerLoaders();
        $this->registerServices();
        $this->registerModules();
        $this->registerErrorHandler();
    }

    public function handle()
    {
        $this->app->setDI($this->di);
        $this->app->handle()->send();
    }

    protected function registerLoaders()
    {
        $this->loader->registerNamespaces([
            'App' => app_path(),
            'Bootstrap' => bootstrap_path(),
        ]);

        $this->loader->registerFiles([
            vendor_path('autoload.php'),
            app_path('Library/Helper.php'),
        ]);

        $this->loader->register();
    }

    protected function registerServices()
    {
        $providers = [
            \App\Providers\Annotation::class,
            \App\Providers\Cache::class,
            \App\Providers\Cookie::class,
            \App\Providers\Config::class,
            \App\Providers\Crypt::class,
            \App\Providers\Database::class,
            \App\Providers\EventsManager::class,
            \App\Providers\Logger::class,
            \App\Providers\MetaData::class,
            \App\Providers\Redis::class,
            \App\Providers\Router::class,
            \App\Providers\Security::class,
            \App\Providers\Session::class,
            \App\Providers\Url::class,
            \App\Providers\Volt::class,
        ];

        foreach ($providers as $provider) {
            $service = new $provider($this->di);
            $service->register();
        }
    }

    protected function registerModules()
    {
        $modules = [
            'api' => [
                'className' => 'App\Http\Api\Module',
                'path' => app_path('Http/Api/Module.php'),
            ],
            'admin' => [
                'className' => 'App\Http\Admin\Module',
                'path' => app_path('Http/Admin/Module.php'),
            ],
            'home' => [
                'className' => 'App\Http\Home\Module',
                'path' => app_path('Http/Home/Module.php'),
            ],
        ];

        $this->app->registerModules($modules);
    }

    protected function registerErrorHandler()
    {
        return new HttpErrorHandler();
    }

}