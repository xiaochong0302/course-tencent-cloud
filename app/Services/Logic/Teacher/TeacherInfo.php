<?php

namespace App\Services\Logic\Teacher;

use App\Services\Logic\Service;
use App\Services\Logic\User\UserInfo as UserInfoService;

class TeacherInfo extends Service
{

    public function handle($id)
    {
        $service = new UserInfoService();

        return $service->handle($id);
    }

}
