<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace Bootstrap;

use App\Providers\Annotation as AnnotationProvider;
use App\Providers\Cache as CacheProvider;
use App\Providers\Config as ConfigProvider;
use App\Providers\Cookie as CookieProvider;
use App\Providers\Crypt as CryptProvider;
use App\Providers\CsrfToken as CsrfTokenProvider;
use App\Providers\Database as DatabaseProvider;
use App\Providers\EventsManager as EventsManagerProvider;
use App\Providers\FlashSession as FlashSessionProvider;
use App\Providers\Logger as LoggerProvider;
use App\Providers\MetaData as MetaDataProvider;
use App\Providers\Provider as AppProvider;
use App\Providers\Request as RequestProvider;
use App\Providers\Response as ResponseProvider;
use App\Providers\Router as RouterProvider;
use App\Providers\Session as SessionProvider;
use App\Providers\Url as UrlProvider;
use App\Providers\View as ViewProvider;
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
        $this->registerLoaders();
        $this->registerServices();
        $this->registerModules();
        $this->registerSettings();
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
            ConfigProvider::class,
            AnnotationProvider::class,
            CacheProvider::class,
            CookieProvider::class,
            CryptProvider::class,
            CsrfTokenProvider::class,
            DatabaseProvider::class,
            EventsManagerProvider::class,
            FlashSessionProvider::class,
            LoggerProvider::class,
            MetaDataProvider::class,
            RequestProvider::class,
            ResponseProvider::class,
            RouterProvider::class,
            SessionProvider::class,
            UrlProvider::class,
            ViewProvider::class,
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
        $aliases = ['api', 'home', 'admin'];

        $modules = [];

        foreach ($aliases as $alias) {
            $moduleName = ucfirst($alias);
            $modules[$alias] = [
                'className' => 'App\Http\\' . $moduleName . '\Module',
                'path' => app_path('Http/' . $moduleName . '/Module.php'),
            ];
        }

        $this->app->registerModules($modules);
    }

    protected function registerErrorHandler()
    {
        return new HttpErrorHandler();
    }

}
