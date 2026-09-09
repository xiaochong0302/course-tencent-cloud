<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

use App\Models\User as UserModel;
use App\Validators\User as UserValidator;

trait UserTrait
{

    protected function checkUser(int $id): UserModel
    {
        $validator = new UserValidator();

        return $validator->checkUser($id);
    }

    protected function checkUserCache(int $id): UserModel
    {
        $validator = new UserValidator();

        return $validator->checkUserCache($id);
    }

}
