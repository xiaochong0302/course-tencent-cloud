<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Validators;

use App\Exceptions\BadRequest as BadRequestException;
use App\Library\Validators\Common as CommonValidator;
use App\Models\CourseUser as CourseUserModel;
use App\Repos\CourseUser as CourseUserRepo;

class CourseUser extends Validator
{

    public function checkCourseUser($courseId, $userId)
    {
        $repo = new CourseUserRepo();

        $courseUser = $repo->findCourseUser($courseId, $userId);

        if (!$courseUser) {
            throw new BadRequestException('course_user.not_found');
        }

        return $courseUser;
    }

    public function checkCourse($id)
    {
        $validator = new Course();

        return $validator->checkCourse($id);
    }

    public function checkUser($name)
    {
        $validator = new Account();

        $account = $validator->checkAccount($name);

        $validator = new User();

        return $validator->checkUser($account->id);
    }

    public function checkExpiryTime($expiryTime)
    {
        $value = $this->filter->sanitize($expiryTime, ['trim', 'string']);

        if (!CommonValidator::date($value, 'Y-m-d H:i:s')) {
            throw new BadRequestException('course_user.invalid_expiry_time');
        }

        return strtotime($value);
    }

    public function checkIfImported($courseId, $userId)
    {
        $repo = new CourseUserRepo();

        $courseUser = $repo->findCourseUser($courseId, $userId);

        if ($courseUser && $courseUser->source_type == CourseUserModel::SOURCE_MANUAL) {
            throw new BadRequestException('course_user.has_imported');
        }
    }

    public function checkIfReviewed($courseId, $userId)
    {
        $repo = new CourseUserRepo();

        $courseUser = $repo->findCourseUser($courseId, $userId);

        if ($courseUser && $courseUser->reviewed) {
            throw new BadRequestException('course_user.has_reviewed');
        }
    }

}
