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

    public function handle(): array
    {
        $user = $this->getLoginUser();

        return $this->handleUser($user);
    }

    protected function handleUser(UserModel $user): array
    {
        $area = $this->handleArea($user->area);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'title' => $user->title,
            'about' => $user->about,
            'gender' => $user->gender,
            'area' => $area,
        ];
    }

    protected function handleArea(string $area): array
    {
        $area = explode('/', $area);

        return [
            'province' => $area[0] ?? '',
            'city' => $area[1] ?? '',
            'county' => $area[2] ?? '',
        ];
    }

}
