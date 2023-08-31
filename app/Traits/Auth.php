<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Traits;

use App\Caches\User as UserCache;
use App\Exceptions\Unauthorized as UnauthorizedException;
use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
use App\Services\Auth as AuthService;
use App\Validators\Validator as AppValidator;
use Phalcon\Di as Di;

trait Auth
{

    /**
     * @param bool $cache
     * @return UserModel
     */
    public function getCurrentUser($cache = false)
    {
        $authUser = $this->getAuthUser();

        if (!$authUser) {
            return $this->getGuestUser();
        }

        if (!$cache) {
            $userRepo = new UserRepo();
            $user = $userRepo->findById($authUser['id']);
        } else {
            $userCache = new UserCache();
            $user = $userCache->get($authUser['id']);
        }

        return $user;
    }

    /**
     * @param bool $cache
     * @return UserModel
     * @throws UnauthorizedException
     */
    public function getLoginUser($cache = false)
    {
        $authUser = $this->getAuthUser();

        $validator = new AppValidator();

        $validator->checkAuthUser($authUser['id']);

        if (!$cache) {
            $userRepo = new UserRepo();
            $user = $userRepo->findById($authUser['id']);
        } else {
            $userCache = new UserCache();
            $user = $userCache->get($authUser['id']);
        }

        return $user;
    }

    /**
     * @return UserModel
     */
    public function getGuestUser()
    {
        $user = new UserModel();

        $user->id = 0;
        $user->name = 'guest';
        $user->avatar = kg_cos_user_avatar_url(null);

        return $user;
    }

    /**
     * @return array|null
     */
    public function getAuthUser()
    {
        /**
         * @var AuthService $auth
         */
        $auth = Di::getDefault()->get('auth');

        return $auth->getAuthInfo();
    }

}
