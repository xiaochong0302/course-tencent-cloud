<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Providers;

use Phalcon\Cli\Dispatcher;

class CliDispatcher extends Provider
{

    protected $serviceName = 'dispatcher';

    public function register()
    {
        $this->di->setShared($this->serviceName, function() {

            $dispatcher = new Dispatcher();

            $dispatcher->setDefaultNamespace('App\Console\Tasks');

            return $dispatcher;
        });
    }

}
