<?php

namespace App\Services\Frontend;

use App\Models\CourseUser as CourseUserModel;
use App\Validators\CourseUser as CourseUserValidator;

class CourseUser extends Service
{

    use CourseTrait;

    public function createUser($id)
    {
        $course = $this->checkCourse($id);

        $user = $this->getLoginUser();

        $validator = new CourseUserValidator();

        $validator->checkIfJoined($course->id, $user->id);

        $courseUser = new CourseUserModel();

        $courseUser->course_id = $course->id;
        $courseUser->user_id = $user->id;
        $courseUser->source_type = CourseUserModel::SOURCE_FREE;
        $courseUser->role_type = CourseUserModel::ROLE_STUDENT;
        $courseUser->expiry_time = strtotime('+3 years');

        $courseUser->create();

        $course->user_count += 1;

        $course->update();
    }

}
