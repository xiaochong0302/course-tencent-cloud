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

    /**
     * @var bool
     */
    protected $ownedCourse = false;

    /**
     * @var bool
     */
    protected $joinedCourse = false;

    /**
     * @var CourseUserModel|null
     */
    protected $courseUser;

    public function setCourseUser(CourseModel $course, UserModel $user)
    {
        if ($user->id == 0) return;

        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseUser($course->id, $user->id);

        $this->courseUser = $courseUser;

        if ($courseUser) {
            $this->joinedCourse = true;
        }

        if ($course->teacher_id == $user->id) {

            $this->ownedCourse = true;

        } elseif ($course->market_price == 0) {

            $this->ownedCourse = true;

        } elseif ($course->vip_price == 0 && $user->vip == 1) {

            $this->ownedCourse = true;

        } elseif ($courseUser) {

            $sourceTypes = [
                CourseUserModel::SOURCE_CHARGE,
                CourseUserModel::SOURCE_MANUAL,
                CourseUserModel::SOURCE_POINT_REDEEM,
                CourseUserModel::SOURCE_LUCKY_REDEEM,
            ];

            $case1 = $courseUser->deleted == 0;
            $case2 = $courseUser->expiry_time > time();
            $case3 = in_array($courseUser->source_type, $sourceTypes);

            /**
             * 之前参与过课程，但不再满足条件，视为未参与
             */
            if ($case1 && $case2 && $case3) {
                $this->ownedCourse = true;
            } else {
                $this->joinedCourse = false;
            }
        }
    }

    protected function assignUserCourse(CourseModel $course, UserModel $user, int $expiryTime, int $sourceType)
    {
        if ($this->allowFreeAccess($course, $user)) return;

        $courseUserRepo = new CourseUserRepo();

        $relation = $courseUserRepo->findCourseUser($course->id, $user->id);

        if (!$relation) {

            $this->createCourseUser($course, $user, $expiryTime, $sourceType);

        } else {

            switch ($relation->source_type) {
                case CourseUserModel::SOURCE_FREE:
                case CourseUserModel::SOURCE_TRIAL:
                case CourseUserModel::SOURCE_VIP:
                case CourseUserModel::SOURCE_TEACHER:
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

    protected function allowFreeAccess(CourseModel $course, UserModel $user)
    {
        $result = false;

        if ($course->market_price == 0) {
            $result = true;
        } elseif ($course->vip_price == 0 && $user->vip == 1) {
            $result = true;
        } elseif($course->teacher_id == $user->id) {
            $result = true;
        }

        return $result;
    }

    protected function getFreeSourceType(CourseModel $course, UserModel $user)
    {
        if ($course->teacher_id == $user->id) {
            return CourseUserModel::SOURCE_TEACHER;
        }

        $sourceType = CourseUserModel::SOURCE_FREE;

        if ($course->market_price > 0) {
            if ($course->vip_price == 0 && $user->vip == 1) {
                $sourceType = CourseUserModel::SOURCE_VIP;
            } else {
                $sourceType = CourseUserModel::SOURCE_TRIAL;
            }
        }

        return $sourceType;
    }

}
