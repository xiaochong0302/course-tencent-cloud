<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use Phalcon\Cli\Dispatcher;

class CliDispatcher extends Provider
{

    protected string $serviceName = 'dispatcher';

    public function register(): void
    {
        $this->di->setShared($this->serviceName, function () {

            $dispatcher = new Dispatcher();

            $dispatcher->setDefaultNamespace('App\Console\Tasks');

            return $dispatcher;
        });
    }

}
