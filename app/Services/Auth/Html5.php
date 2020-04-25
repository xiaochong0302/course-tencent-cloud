<?php

namespace App\Services\Auth;

use App\Models\User as UserModel;
use App\Services\Auth as AuthService;

class Html5 extends AuthService
{

    public function saveAuthInfo(UserModel $user)
    {
        $authKey = $this->getAuthKey();

        $authInfo = [
            'id' => $user->id,
            'name' => $user->name,
        ];

        $this->session->set($authKey, $authInfo);
    }

    public function clearAuthInfo()
    {
        $authKey = $this->getAuthKey();

        $this->session->remove($authKey);
    }

    public function getAuthInfo()
    {
        $authKey = $this->getAuthKey();

        $authInfo = $this->session->get($authKey);

        return $authInfo ?: null;
    }

    public function getAuthKey()
    {
        return 'h5_auth_info';
    }

}
