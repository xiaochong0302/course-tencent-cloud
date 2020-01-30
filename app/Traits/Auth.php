<?php

namespace App\Traits;

use App\Models\User as UserModel;
use App\Validators\Validator;

trait Auth
{

    public function getCurrentUser()
    {
        $authUser = $this->getAuthUser();

        if ($authUser) {
            $user = UserModel::findFirst($authUser->id);
        } else {
            $user = new UserModel();
        }

        return $user;
    }

    public function getLoginUser()
    {
        $authUser = $this->getAuthUser();

        $validator = new Validator();

        $validator->checkAuthUser($authUser);

        $user = UserModel::findFirst($authUser->id);

        return $user;
    }

    public function getAuthUser()
    {
        $auth = $this->getDI()->get('auth');

        return $auth->getAuthInfo();
    }

}
