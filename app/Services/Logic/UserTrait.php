<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

use App\Repos\User as UserRepo;
use App\Validators\User as UserValidator;

trait UserTrait
{

    public function checkUser($id)
    {
        $validator = new UserValidator();

        return $validator->checkUser($id);
    }

    public function checkUserCache($id)
    {
        $validator = new UserValidator();

        return $validator->checkUserCache($id);
    }

    public function handleShallowUserInfo($id)
    {
        if (empty($id)) return new \stdClass();

        $userRepo = new UserRepo();

        $user = $userRepo->findShallowUserById($id);

        if (!$user) return new \stdClass();

        $result = $user->toArray();

        $result['avatar'] = kg_cos_user_avatar_url($user->avatar);

        return $result;
    }

}
