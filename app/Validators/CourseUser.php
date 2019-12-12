<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validator\Common as CommonValidator;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\User as UserRepo;

class CourseUser extends Validator
{

    /**
     * @param integer $courseId
     * @param integer $userId
     * @return \App\Models\CourseUser
     * @throws BadRequestException
     */
    public function checkCourseStudent($courseId, $userId)
    {
        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseStudent($courseId, $userId);

        if (!$courseUser) {
            throw new BadRequestException('course_student.not_found');
        }

        return $courseUser;
    }

    public function checkCourseId($courseId)
    {
        $value = $this->filter->sanitize($courseId, ['trim', 'int']);

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($value);

        if (!$course) {
            throw new BadRequestException('course_student.course_not_found');
        }

        return $course->id;
    }

    public function checkUserId($userId)
    {
        $value = $this->filter->sanitize($userId, ['trim', 'int']);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($value);

        if (!$user) {
            throw new BadRequestException('course_student.user_not_found');
        }

        return $user->id;
    }

    public function checkExpireTime($expireTime)
    {
        $value = $this->filter->sanitize($expireTime, ['trim', 'string']);

        if (!CommonValidator::date($value, 'Y-m-d H:i:s')) {
            throw new BadRequestException('course_student.invalid_expire_time');
        }

        return strtotime($value);
    }

    public function checkLockStatus($status)
    {
        $value = $this->filter->sanitize($status, ['trim', 'int']);

        if (!in_array($value, [0, 1])) {
            throw new BadRequestException('course_student.invalid_lock_status');
        }

        return $value;
    }

    public function checkIfJoined($courseId, $userId)
    {
        $repo = new CourseUserRepo();

        $courseUser = $repo->findCourseStudent($courseId, $userId);

        if ($courseUser) {
            throw new BadRequestException('course_student.user_has_joined');
        }
    }

}
