<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Traits;

use App\Caches\User as UserCache;
use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
use App\Services\Auth as AuthService;
use App\Validators\Validator as AppValidator;
use Phalcon\Di\Di as Di;

trait Auth
{

    protected function getCurrentUser(bool $cache = false): UserModel
    {
        /**
         * @var AuthService $auth
         */
        $auth = Di::getDefault()->get('auth');

        $authInfo = $auth->getAuthInfo();

        if (!$authInfo) {
            return $this->getGuestUser();
        }

        if (!$cache) {
            $userRepo = new UserRepo();
            $user = $userRepo->findById($authInfo['id']);
        } else {
            $userCache = new UserCache();
            $user = $userCache->get($authInfo['id']);
        }

        return $user;
    }

    protected function getLoginUser(bool $cache = false): UserModel
    {
        /**
         * @var AuthService $auth
         */
        $auth = Di::getDefault()->get('auth');

        $authInfo = $auth->getAuthInfo();

        $validator = new AppValidator();

        $validator->checkAuthInfo($authInfo);

        if (!$cache) {
            $userRepo = new UserRepo();
            $user = $userRepo->findById($authInfo['id']);
        } else {
            $userCache = new UserCache();
            $user = $userCache->get($authInfo['id']);
        }

        return $user;
    }

    private function getGuestUser(): UserModel
    {
        $user = new UserModel();

        $user->id = 0;
        $user->name = 'guest';
        $user->avatar = kg_cos_user_avatar_url();

        return $user;
    }

}
