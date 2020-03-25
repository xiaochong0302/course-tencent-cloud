<?php

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validator\Common as CommonValidator;
use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\User as UserRepo;

class CourseUser extends Validator
{

    public function checkCourseUser($id)
    {
        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findById($id);

        if (!$courseUser) {
            throw new BadRequestException('course_user.not_found');
        }

        return $courseUser;
    }

    public function checkCourseId($courseId)
    {
        $value = $this->filter->sanitize($courseId, ['trim', 'int']);

        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($value);

        if (!$course) {
            throw new BadRequestException('course_user.course_not_found');
        }

        return $course->id;
    }

    public function checkUserId($userId)
    {
        $value = $this->filter->sanitize($userId, ['trim', 'int']);

        $userRepo = new UserRepo();

        $user = $userRepo->findById($value);

        if (!$user) {
            throw new BadRequestException('course_user.user_not_found');
        }

        return $user->id;
    }

    public function checkExpiryTime($expiryTime)
    {
        $value = $this->filter->sanitize($expiryTime, ['trim', 'string']);

        if (!CommonValidator::date($value, 'Y-m-d H:i:s')) {
            throw new BadRequestException('course_user.invalid_expiry_time');
        }

        return strtotime($value);
    }

    public function checkIfAllowApply(CourseModel $course, UserModel $user)
    {
        $caseA = $course->market_price > 0;
        $caseB = $user->vip == 0 && $course->vip_price > 0;

        if ($caseA || $caseB) {
            throw new BadRequestException('course_user.apply_not_allowed');
        }
    }

    public function checkIfJoined($courseId, $userId)
    {
        $repo = new CourseUserRepo();

        $courseUser = $repo->findCourseStudent($courseId, $userId);

        if ($courseUser) {
            throw new BadRequestException('course_user.has_joined_course');
        }
    }

}
