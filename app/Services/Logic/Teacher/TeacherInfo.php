<?php

namespace App\Services\Logic\Teacher;

use App\Services\Logic\Service as LogicService;
use App\Services\Logic\User\UserInfo as UserInfoService;

class TeacherInfo extends LogicService
{

    public function handle($id)
    {
        $service = new UserInfoService();

        return $service->handle($id);
    }

}
