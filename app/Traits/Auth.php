<?php

namespace App\Traits;

use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
use App\Services\AuthUser as AuthUserService;
use App\Validators\Validator as AppValidator;

trait Auth
{

    public function getCurrentUser()
    {
        $authUser = $this->getAuthUser();

        if (!$authUser) {
            return $this->getGuestUser();
        }

        $userRepo = new UserRepo();

        $user = $userRepo->findById($authUser->id);

        return $user;
    }

    public function getLoginUser()
    {
        $authUser = $this->getAuthUser();

        $validator = new AppValidator();

        $validator->checkAuthUser($authUser);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($authUser->id);

        return $user;
    }

    public function getGuestUser()
    {
        $user = new UserModel();

        $user->id = 0;
        $user->name = 'guest';

        return $user;
    }

    public function getAuthUser()
    {
        /**
         * @var AuthUserService $auth
         */
        $auth = $this->getDI()->get('auth');

        return $auth->getAuthInfo();
    }

}
