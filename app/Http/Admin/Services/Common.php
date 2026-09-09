<?php
/**
 * @copyright Copyright (c) 2024 深圳市酷瓜软件有限公司
 * @license https://www.koogua.net/wuwei/pro-license
 * @link https://www.koogua.net
 */

namespace App\Http\Admin\Services;

use App\Services\Auth\Admin as AdminAuth;

class Common extends Service
{

    public function getAuthInfo(): ?array
    {
        /**
         * @var AdminAuth $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getAuthInfo();
    }

}
