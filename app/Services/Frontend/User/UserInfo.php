<?php

namespace App\Services\Frontend\User;

use App\Models\User as UserModel;
use App\Services\Frontend\Service;
use App\Services\Frontend\UserTrait;

class UserInfo extends Service
{

    use UserTrait;

    public function getUser($id)
    {
        $user = $this->checkUser($id);

        return $this->handleUser($user);
    }

    /**
     * @param UserModel $user
     * @return array
     */
    protected function handleUser($user)
    {
        $user->avatar = kg_img_url($user->avatar);
        $user->vip = $user->vip == 1;
        $user->locked = $user->locked == 1;

        $result = [
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

        return $result;
    }

}
