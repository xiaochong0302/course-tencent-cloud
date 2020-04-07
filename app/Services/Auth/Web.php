<?php

namespace App\Services\Auth;

use App\Models\User as UserModel;
use App\Services\Auth as AuthService;
use Yansongda\Supports\Collection;

class Web extends AuthService
{

    /**
     * 写入会话
     *
     * @param UserModel $user
     */
    public function saveAuthInfo(UserModel $user)
    {
        $authKey = $this->getAuthKey();

        $authInfo = new Collection([
            'id' => $user->id,
            'name' => $user->name,
        ]);

        $this->session->set($authKey, $authInfo);
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
     * @return Collection
     */
    public function getAuthInfo()
    {
        $authKey = $this->getAuthKey();

        $authInfo = $this->session->get($authKey);

        $items = $authInfo ? $authInfo : [];

        return new Collection($items);
    }

    /**
     * 获取会话键值
     *
     * @return string
     */
    public function getAuthKey()
    {
        return 'web_auth_info';
    }

}
