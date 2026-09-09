<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

class Router extends Provider
{

    protected string $serviceName = 'router';

    public function register(): void
    {
        $this->di->setShared($this->serviceName, function () {
            return require config_path('routes.php');
        });
    }

}
