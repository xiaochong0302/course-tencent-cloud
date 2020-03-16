<?php

namespace App\Services\Frontend;

use App\Models\User as UserModel;
use App\Validators\Validator as AppValidator;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\User\Component;

class Service extends Component
{

    /**
     * @return UserModel|Model
     */
    public function getCurrentUser()
    {
        $user = UserModel::findFirst(100015);

        return $user;
    }

    /**
     * @return UserModel|Model
     */
    public function getCurrentUser2()
    {
        $authUser = $this->getAuthUser();

        if ($authUser) {
            $user = UserModel::findFirst($authUser->id);
        } else {
            $user = $this->getGuestUser();
        }

        return $user;
    }

    /**
     * @return UserModel|Model
     */
    public function getLoginUser()
    {
        $user = UserModel::findFirst(100015);

        return $user;
    }

    /**
     * @return UserModel|Model
     */
    public function getLoginUser2()
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
