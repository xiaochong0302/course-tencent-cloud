<?php

namespace App\Services\Frontend\Teacher;

use App\Services\Frontend\Service as FrontendService;
use App\Services\Frontend\User\UserInfo as UserInfoService;

class TeacherInfo extends FrontendService
{

    public function getUser($id)
    {
        $service = new UserInfoService();

        return $service->handle($id);
    }

}
