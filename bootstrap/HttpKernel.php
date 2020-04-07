<?php

namespace Bootstrap;

use App\Providers\Annotation as AnnotationProvider;
use App\Providers\Cache as CacheProvider;
use App\Providers\Config as ConfigProvider;
use App\Providers\Cookie as CookieProvider;
use App\Providers\Crypt as CryptProvider;
use App\Providers\Database as DatabaseProvider;
use App\Providers\EventsManager as EventsManagerProvider;
use App\Providers\Logger as LoggerProvider;
use App\Providers\MetaData as MetaDataProvider;
use App\Providers\Provider as AppProvider;
use App\Providers\Router as RouterProvider;
use App\Providers\Security as SecurityProvider;
use App\Providers\Session as SessionProvider;
use App\Providers\Url as UrlProvider;
use App\Providers\Volt as VoltProvider;
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\Application;

class HttpKernel extends Kernel
{

    public function __construct()
    {
        $this->di = new FactoryDefault();
        $this->app = new Application();
        $this->loader = new Loader();

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
            AnnotationProvider::class,
            CacheProvider::class,
            CookieProvider::class,
            ConfigProvider::class,
            CryptProvider::class,
            DatabaseProvider::class,
            EventsManagerProvider::class,
            LoggerProvider::class,
            MetaDataProvider::class,
            RouterProvider::class,
            SecurityProvider::class,
            SessionProvider::class,
            UrlProvider::class,
            VoltProvider::class,
        ];

        foreach ($providers as $provider) {
            /**
             * @var AppProvider $service
             */
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
            'web' => [
                'className' => 'App\Http\Web\Module',
                'path' => app_path('Http/Web/Module.php'),
            ],
            'html5' => [
                'className' => 'App\Http\Html5\Module',
                'path' => app_path('Http/Html5/Module.php'),
            ],
        ];

        $this->app->registerModules($modules);
    }

    protected function registerErrorHandler()
    {
        return new HttpErrorHandler();
    }

}