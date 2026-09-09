<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace Bootstrap;

use App\Providers\Cache as CacheProvider;
use App\Providers\CliDispatcher as DispatcherProvider;
use App\Providers\Config as ConfigProvider;
use App\Providers\Crypt as CryptProvider;
use App\Providers\Database as DatabaseProvider;
use App\Providers\EventsManager as EventsManagerProvider;
use App\Providers\Logger as LoggerProvider;
use App\Providers\MetaData as MetaDataProvider;
use App\Providers\Provider as AppProvider;
use App\Providers\Redis as RedisProvider;
use Phalcon\Autoload\Loader;
use Phalcon\Cli\Console;
use Phalcon\Di\FactoryDefault\Cli as CliDi;
use Phalcon\Support\HelperFactory;

class ConsoleKernel extends Kernel
{

    public function __construct()
    {
        $this->di = new CliDi();
        $this->app = new Console();
        $this->loader = new Loader();

        $this->initAppEnv();
        $this->registerLoaders();
        $this->registerServices();
        $this->registerSettings();
        $this->registerErrorHandler();
    }

    public function handle(): void
    {
        $this->app->setDI($this->di);

        $options = getopt('', ['task:', 'action:']);

        if (!empty($options['task']) && !empty($options['action'])) {

            $this->app->handle($options);

        } else {

            $options = $this->parseCommandLineArguments();

            $this->app->handle($options);

            echo PHP_EOL . PHP_EOL;
        }
    }

    protected function registerLoaders(): void
    {
        $this->loader->setNamespaces([
            'App' => app_path(),
            'Bootstrap' => bootstrap_path(),
        ]);

        $this->loader->setFiles([
            vendor_path('autoload.php'),
            app_path('Library/Helper.php'),
        ]);

        $this->loader->register();
    }

    protected function registerServices(): void
    {
        $providers = [
            ConfigProvider::class,
            CacheProvider::class,
            RedisProvider::class,
            CryptProvider::class,
            DatabaseProvider::class,
            EventsManagerProvider::class,
            LoggerProvider::class,
            MetaDataProvider::class,
            DispatcherProvider::class,
        ];

        foreach ($providers as $provider) {
            /**
             * @var AppProvider $service
             */
            $service = new $provider($this->di);

            $service->register();
        }
    }

    protected function registerErrorHandler(): void
    {
        new ConsoleErrorHandler();
    }

    protected function handleTaskName(string $name): string
    {
        $helperFactory = new HelperFactory();

        return $helperFactory->uncamelize($name);
    }

    protected function handleActionName(string $name): string
    {
        $helperFactory = new HelperFactory();

        $name = $helperFactory->uncamelize($name);

        return lcfirst($helperFactory->camelize($name));
    }

    private function parseCommandLineArguments(): array
    {
        $options = [];

        foreach ($_SERVER['argv'] as $k => $arg) {
            if ($k == 1) {
                $options['task'] = $this->handleTaskName($arg);
            } elseif ($k == 2) {
                $options['action'] = $this->handleActionName($arg);
            } elseif ($k >= 3) {
                $options['params'][] = $arg;
            }
        }

        return $options;
    }

}
