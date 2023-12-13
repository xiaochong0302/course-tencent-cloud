<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Deliver;

use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\User as UserModel;
use App\Repos\CourseUser as CourseUserRepo;
use App\Services\Logic\Course\CourseUserTrait;
use App\Services\Logic\Service as LogicService;

class CourseDeliver extends LogicService
{

    use CourseUserTrait;

    public function handle(CourseModel $course, UserModel $user)
    {
        $this->revokeCourseUser($course, $user);
        $this->handleCourseUser($course, $user);
    }

    protected function handleCourseUser(CourseModel $course, UserModel $user)
    {
        $expiryTime = strtotime("+{$course->study_expiry} months");

        if ($course->model == CourseModel::MODEL_OFFLINE) {
            $expiryTime = strtotime($course->attrs['end_date']);
        }

        $sourceType = CourseUserModel::SOURCE_CHARGE;

        $this->createCourseUser($course, $user, $expiryTime, $sourceType);
        $this->recountCourseUsers($course);
        $this->recountUserCourses($user);
    }

    protected function revokeCourseUser(CourseModel $course, UserModel $user)
    {
        $courseUserRepo = new CourseUserRepo();

        $relations = $courseUserRepo->findByCourseAndUserId($course->id, $user->id);

        if ($relations->count() == 0) return;

        foreach ($relations as $relation) {
            if ($relation->deleted == 0) {
                $this->deleteCourseUser($relation);
            }
        }
    }

}
