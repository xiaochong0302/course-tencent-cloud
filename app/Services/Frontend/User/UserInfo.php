<?php

namespace App\Services\Frontend\User;

use App\Models\User as UserModel;
use App\Services\Frontend\Service as FrontendService;
use App\Services\Frontend\UserTrait;

class UserInfo extends FrontendService
{

    use UserTrait;

    public function handle($id)
    {
        $user = $this->checkUser($id);

        return $this->handleUser($user);
    }

    protected function handleUser(UserModel $user)
    {
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
            'active_time' => $user->active_time,
            'create_time' => $user->create_time,
        ];
    }

}
