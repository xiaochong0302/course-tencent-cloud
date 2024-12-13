<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User\Console;

use App\Models\User as UserModel;
use App\Services\Logic\Service as LogicService;

class ProfileInfo extends LogicService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        return $this->handleUser($user);
    }

    protected function handleUser(UserModel $user)
    {
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
            'edu_role' => $user->edu_role,
            'admin_role' => $user->admin_role,
            'vip_expiry_time' => $user->vip_expiry_time,
            'lock_expiry_time' => $user->lock_expiry_time,
            'create_time' => $user->create_time,
            'update_time' => $user->update_time,
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
