<?php

namespace App\Services\Frontend\Course;

use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service as FrontendService;

class CourseBasicInfo extends FrontendService
{

    use CourseTrait;
    use CourseBasicInfoTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);

        return $this->handleBasicInfo($course);
    }

}
