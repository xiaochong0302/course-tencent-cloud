<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\User as UserModel;
use App\Repos\CourseUser as CourseUserRepo;

class CourseUser extends Validator
{

    public function checkById(int $id): CourseUserModel
    {
        $repo = new CourseUserRepo();

        $courseUser = $repo->findById($id);

        if (!$courseUser) {
            throw new BadRequestException('course_user.not_found');
        }

        return $courseUser;
    }

    public function checkCourseUser(int $courseId, int $userId): CourseUserModel
    {
        $repo = new CourseUserRepo();

        $courseUser = $repo->findCourseUser($courseId, $userId);

        if (!$courseUser) {
            throw new BadRequestException('course_user.not_found');
        }

        return $courseUser;
    }

    public function checkCourse(int $id): CourseModel
    {
        $validator = new Course();

        return $validator->checkCourse($id);
    }

    public function checkUser(string|int $name): UserModel
    {
        $validator = new Account();

        $account = $validator->checkAccount($name);

        $validator = new User();

        return $validator->checkUser($account->id);
    }

    public function checkExpiryTime(string $expiryTime): int
    {
        $value = $this->filter->sanitize($expiryTime, ['trim', 'string']);

        if (!CommonValidator::date($value, 'Y-m-d H:i:s')) {
            throw new BadRequestException('course_user.invalid_expiry_time');
        }

        return strtotime($value);
    }

    public function checkIfAllowReview(int $courseId, int $userId): void
    {
        $repo = new CourseUserRepo();

        $courseUser = $repo->findCourseUser($courseId, $userId);

        if ($courseUser) {
            if ($courseUser->reviewed == 1) {
                throw new BadRequestException('course_user.has_reviewed');
            } elseif ($courseUser->progress < 30) {
                throw new BadRequestException('course_user.progress_too_low');
            }
        }
    }

}
