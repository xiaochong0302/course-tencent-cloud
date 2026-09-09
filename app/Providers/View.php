<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use App\Library\Mvc\View as MyView;

class View extends Provider
{

    protected string $serviceName = 'view';

    public function register(): void
    {
        $this->di->setShared($this->serviceName, function () {
            return new MyView();
        });
    }

}
