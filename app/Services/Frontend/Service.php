<?php

namespace App\Services\Frontend;

use App\Models\User as UserModel;
use App\Validators\Validator as AppValidator;

class Service extends \Phalcon\Mvc\User\Component
{

    public function getCurrentUser()
    {
        $authUser = $this->getAuthUser();

        if ($authUser) {
            $user = UserModel::findFirst($authUser->id);
        } else {
            $user = $this->getGuestUser();
        }

        return $user;
    }

    public function getLoginUser()
    {
        $authUser = $this->getAuthUser();

        $validator = new AppValidator();

        $validator->checkAuthUser($authUser);

        $user = UserModel::findFirst($authUser->id);

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
