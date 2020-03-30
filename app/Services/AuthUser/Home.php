<?php

namespace App\Services\AuthUser;

use App\Models\User as UserModel;
use App\Services\AuthUser;

class Home extends AuthUser
{

    /**
     * 写入会话
     *
     * @param UserModel $user
     */
    public function saveAuthInfo(UserModel $user)
    {
        $authKey = $this->getAuthKey();

        $authUser = new \stdClass();

        $authUser->id = $user->id;
        $authUser->name = $user->name;
        $authUser->avatar = $user->avatar;
        $authUser->admin_role = $user->admin_role;
        $authUser->edu_role = $user->edu_role;

        $this->session->set($authKey, $authUser);
    }

    /**
     * 清除会话
     */
    public function clearAuthInfo()
    {
        $authKey = $this->getAuthKey();

        $this->session->remove($authKey);
    }

    /**
     * 读取会话
     *
     * @return mixed
     */
    public function getAuthInfo()
    {
        $authKey = $this->getAuthKey();

        return $this->session->get($authKey);
    }

    /**
     * 获取会话键值
     *
     * @return string
     */
    public function getAuthKey()
    {
        return 'user_info';
    }

}
