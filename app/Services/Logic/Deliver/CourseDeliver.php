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
use App\Services\Logic\Service as LogicService;

class CourseDeliver extends LogicService
{

    public function handle(CourseModel $course, UserModel $user)
    {
        $this->revokeCourseUser($course, $user);
        $this->handleCourseUser($course, $user);
    }

    protected function handleCourseUser(CourseModel $course, UserModel $user)
    {
        if ($course->model == CourseModel::MODEL_OFFLINE) {
            $expiryTime = strtotime($course->attrs['end_date']);
        } else {
            $expiryTime = strtotime("+{$course->study_expiry} months");
        }

        $courseUser = new CourseUserModel();
        $courseUser->user_id = $user->id;
        $courseUser->course_id = $course->id;
        $courseUser->expiry_time = $expiryTime;
        $courseUser->role_type = CourseUserModel::ROLE_STUDENT;
        $courseUser->source_type = CourseUserModel::SOURCE_CHARGE;
        $courseUser->create();

        $course->user_count += 1;
        $course->update();

        $user->course_count += 1;
        $user->update();
    }

    protected function revokeCourseUser(CourseModel $course, UserModel $user)
    {
        $courseUserRepo = new CourseUserRepo();

        $relations = $courseUserRepo->findByCourseAndUserId($course->id, $user->id);

        if ($relations->count() == 0) return;

        foreach ($relations as $relation) {
            if ($relation->deleted == 0) {
                $relation->deleted = 1;
                $relation->update();
            }
        }
    }

}
