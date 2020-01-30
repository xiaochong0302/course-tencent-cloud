<?php

namespace App\Services\Frontend;

use App\Caches\CourseUser as CourseUserCache;
use App\Validators\Course as CourseValidator;

trait CourseTrait
{

    public function checkCourse($id)
    {
        $validator = new CourseValidator();

        $course = $validator->checkCourse($id);

        return $course;
    }

    public function checkCourseCache($id)
    {
        $validator = new CourseValidator();

        $course = $validator->checkCourseCache($id);

        return $course;
    }

    /**
     * @param $courseId
     * @param $userId
     * @return \App\Models\CourseUser|null
     */
    public function getCourseUser($courseId, $userId)
    {
        if (!$courseId || !$userId) {
            return null;
        }

        $key = "{$courseId}_{$userId}";

        $courseUserCache = new CourseUserCache();

        $courseUser = $courseUserCache->get($key);

        return $courseUser;
    }

    /**
     * @param \App\Models\Course $course
     * @param \App\Models\User $user
     * @return bool
     */
    public function ownedCourse($course, $user)
    {
        if ($course->market_price == 0) {
            return true;
        }

        if ($course->vip_price == 0 && $user->vip == 1) {
            return true;
        }

        if ($user->id == 0) {
            return false;
        }

        $courseUser = $this->getCourseUser($course->id, $user->id);

        $caseAOk = $courseUser->deleted == 0;
        $caseBOk = $courseUser->expire_time < time();

        if ($courseUser && $caseAOk && $caseBOk) {
            return true;
        }

        return false;
    }

}
