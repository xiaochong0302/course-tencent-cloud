<?php

namespace App\Services\Logic\User\Console;

use App\Services\Logic\Service;
use App\Services\Logic\User\CourseList as UserCourseListService;

class CourseList extends Service
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $service = new UserCourseListService();

        return $service->handle($user->id);
    }

}
