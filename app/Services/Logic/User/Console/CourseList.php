<?php

namespace App\Services\Logic\User\Console;

use App\Services\Logic\Service as LogicService;
use App\Services\Logic\User\CourseList as UserCourseListService;

class CourseList extends LogicService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $service = new UserCourseListService();

        return $service->handle($user->id);
    }

}
