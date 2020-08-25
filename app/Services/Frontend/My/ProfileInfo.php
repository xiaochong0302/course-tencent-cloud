<?php

namespace App\Services\Frontend\My;

use App\Models\User as UserModel;
use App\Services\Frontend\Service as FrontendService;

class ProfileInfo extends FrontendService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        return $this->handleUser($user);
    }

    protected function handleUser(UserModel $user)
    {
        $user->avatar = kg_ci_img_url($user->avatar);

        $user->area = $this->handleArea($user->area);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'title' => $user->title,
            'about' => $user->about,
            'area' => $user->area,
            'gender' => $user->gender,
            'vip' => $user->vip,
            'locked' => $user->locked,
            'vip_expiry_time' => $user->vip_expiry_time,
            'lock_expiry_time' => $user->lock_expiry_time,
            'edu_role' => $user->edu_role,
            'admin_role' => $user->admin_role,
        ];
    }

    protected function handleArea($area)
    {
        $area = explode('/', $area);

        return [
            'province' => $area[0] ?? '',
            'city' => $area[1] ?? '',
            'county' => $area[2] ?? '',
        ];
    }

}
