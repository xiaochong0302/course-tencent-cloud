<?php

namespace App\Services\Frontend;

use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
use App\Validators\Validator as AppValidator;
use Phalcon\Mvc\User\Component;

class Service extends Component
{

    public function getCurrentUser()
    {
        $userRepo = new UserRepo();

        $user = $userRepo->findById(100015);

        return $user;
    }

    public function getCurrentUser2()
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
        $userRepo = new UserRepo();

        $user = $userRepo->findById(100015);

        return $user;
    }

    public function getLoginUser2()
    {
        $authUser = $this->getAuthUser();

        $validator = new AppValidator();

        $validator->checkAuthUser($authUser);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($authUser->id);

        return $user;
    }

    public function getAuthUser()
    {
        $auth = $this->getDI()->get('auth');

        return $auth->getAuthInfo();
    }

    public function getGuestUser()
    {
        $user = new UserModel();

        $user->id = 0;
        $user->name = 'guest';

        return $user;
    }

}
