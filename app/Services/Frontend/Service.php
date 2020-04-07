<?php

namespace App\Services\Frontend;

use App\Models\User as UserModel;
use App\Repos\User as UserRepo;
use App\Services\Auth as AuthService;
use App\Validators\Validator as AppValidator;
use Phalcon\Mvc\User\Component;

class Service extends Component
{

    public function getCurrentUser()
    {
        $authUser = $this->getAuthUser();

        if (!$authUser) {
            return $this->getGuestUser();
        }

        $userRepo = new UserRepo();

        return $userRepo->findById($authUser['id']);
    }

    public function getLoginUser()
    {
        $authUser = $this->getAuthUser();

        $validator = new AppValidator();

        $validator->checkAuthUser($authUser);

        dd($authUser);

        $userRepo = new UserRepo();

        return $userRepo->findById($authUser['id']);
    }

    public function getAuthUser()
    {
        /**
         * @var AuthService $auth
         */
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
