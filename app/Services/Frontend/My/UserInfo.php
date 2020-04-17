<?php

namespace App\Services\Frontend\My;

use App\Models\User as UserModel;
use App\Services\Frontend\Service;

class UserInfo extends Service
{

    public function getUser()
    {
        $user = $this->getLoginUser();

        return $this->handleUser($user);
    }

    protected function handleUser(UserModel $user)
    {
        $user->avatar = kg_ci_img_url($user->avatar);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'title' => $user->title,
            'about' => $user->about,
            'location' => $user->location,
            'gender' => $user->gender,
            'vip' => $user->vip,
            'locked' => $user->locked,
            'vip_expiry_time' => $user->vip_expiry_time,
            'lock_expiry_time' => $user->lock_expiry_time,
            'edu_role' => $user->edu_role,
            'admin_role' => $user->admin_role,
            'notice_count' => $user->notice_count,
            'msg_count' => $user->msg_count,
        ];
    }

}
