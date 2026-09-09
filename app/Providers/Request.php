<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Providers;

use App\Library\Http\Request as MyRequest;

class Request extends Provider
{

    protected string $serviceName = 'request';

    public function register(): void
    {
        $this->di->setShared($this->serviceName, function () {
            return new MyRequest();
        });
    }

}
