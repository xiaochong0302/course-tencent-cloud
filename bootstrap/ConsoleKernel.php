<?php

namespace Bootstrap;

class ConsoleKernel extends Kernel
{

    public function __construct()
    {
        $this->di = new \Phalcon\Di\FactoryDefault\Cli();
        $this->app = new \Phalcon\Cli\Console();
        $this->loader = new \Phalcon\Loader();

        $this->initAppEnv();
        $this->initAppConfigs();
        $this->initAppSettings();
        $this->registerLoaders();
        $this->registerServices();
        $this->registerErrorHandler();
    }

    public function handle()
    {
        $this->app->setDI($this->di);

        $options = getopt('', ['task:', 'action:']);

        if (!empty($options['task']) && !empty($options['action'])) {

            $this->app->handle($options);

        } else {

            $options = [];

            foreach ($_SERVER['argv'] as $k => $arg) {
                if ($k == 1) {
                    $options['task'] = $arg;
                } elseif ($k == 2) {
                    $options['action'] = $arg;
                } elseif ($k >= 3) {
                    $options['params'][] = $arg;
                }
            }

            $this->app->handle($options);

            echo PHP_EOL . PHP_EOL;
        }
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
            \App\Providers\Cache::class,
            \App\Providers\Config::class,
            \App\Providers\Crypt::class,
            \App\Providers\Database::class,
            \App\Providers\EventsManager::class,
            \App\Providers\Logger::class,
            \App\Providers\MetaData::class,
            \App\Providers\Redis::class,
            \App\Providers\CliDispatcher::class,
        ];

        foreach ($providers as $provider) {
            $service = new $provider($this->di);
            $service->register();
        }
    }

    protected function registerErrorHandler()
    {
        return new ConsoleErrorHandler();
    }

}