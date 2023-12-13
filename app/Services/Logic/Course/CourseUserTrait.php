<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Course;

use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\User as UserModel;
use App\Repos\Course as CourseRepo;
use App\Repos\CourseUser as CourseUserRepo;
use App\Repos\User as UserRepo;

trait CourseUserTrait
{

    protected function assignUserCourse(CourseModel $course, UserModel $user, int $expiryTime, int $sourceType)
    {
        $courseUserRepo = new CourseUserRepo();

        $relation = $courseUserRepo->findCourseUser($course->id, $user->id);

        if (!$relation) {

            $relation = $this->createCourseUser($course, $user, $expiryTime, $sourceType);

        } else {

            switch ($relation->source_type) {
                case CourseUserModel::SOURCE_FREE:
                case CourseUserModel::SOURCE_TRIAL:
                    $this->createCourseUser($course, $user, $expiryTime, $sourceType);
                    $this->deleteCourseUser($relation);
                    break;
                case CourseUserModel::SOURCE_MANUAL:
                    $relation->expiry_time = $expiryTime;
                    $relation->update();
                    break;
                case CourseUserModel::SOURCE_CHARGE:
                case CourseUserModel::SOURCE_POINT_REDEEM:
                case CourseUserModel::SOURCE_LUCKY_REDEEM:
                    if ($relation->expiry_time < time()) {
                        $this->createCourseUser($course, $user, $expiryTime, $sourceType);
                        $this->deleteCourseUser($relation);
                    }
                    break;
            }
        }

        $this->recountCourseUsers($course);
        $this->recountUserCourses($user);

        return $relation;
    }

    protected function createCourseUser(CourseModel $course, UserModel $user, int $expiryTime, int $sourceType)
    {
        $courseUser = new CourseUserModel();

        $courseUser->course_id = $course->id;
        $courseUser->user_id = $user->id;
        $courseUser->expiry_time = $expiryTime;
        $courseUser->source_type = $sourceType;

        $courseUser->create();

        return $courseUser;
    }

    protected function deleteCourseUser(CourseUserModel $relation)
    {
        $relation->deleted = 1;

        $relation->update();
    }

    protected function recountCourseUsers(CourseModel $course)
    {
        $courseRepo = new CourseRepo();

        $userCount = $courseRepo->countUsers($course->id);

        $course->user_count = $userCount;

        $course->update();
    }

    protected function recountUserCourses(UserModel $user)
    {
        $userRepo = new UserRepo();

        $courseCount = $userRepo->countCourses($user->id);

        $user->course_count = $courseCount;

        $user->update();
    }

}
