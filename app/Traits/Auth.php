<?php

namespace App\Traits;

use App\Caches\User as UserCache;
use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
use App\Services\Auth as AuthService;
use App\Validators\Validator as AppValidator;
use Phalcon\Di;

trait Auth
{

    /**
     * @return UserModel
     */
    public function getCurrentUser()
    {
        $authUser = $this->getAuthUser();

        if (!$authUser) {
            return $this->getGuestUser();
        }

        $userCache = new UserCache();

        return $userCache->get($authUser['id']);
    }

    /**
     * @return UserModel
     */
    public function getLoginUser()
    {
        $userRepo = new UserRepo();

        return $userRepo->findById(100015);

        $authUser = $this->getAuthUser();

        $validator = new AppValidator();

        $validator->checkAuthUser($authUser);

        $userCache = new UserCache();

        return $userCache->get($authUser['id']);
    }

    /**
     * @return UserModel
     */
    public function getGuestUser()
    {
        $user = new UserModel();

        $user->id = 0;
        $user->name = 'guest';

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
