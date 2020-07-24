<?php

namespace App\Services\Frontend\My;

use App\Services\Frontend\Service as FrontendService;
use App\Services\Frontend\User\CourseList as UserCourseListService;

class CourseList extends FrontendService
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $service = new UserCourseListService();

        return $service->handle($user->id);
    }

}
