<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services;

use App\Models\User as UserModel;

abstract class Auth extends Service
{

    abstract function saveAuthInfo(UserModel $user);

    abstract function getAuthInfo();

    abstract function clearAuthInfo();

}
