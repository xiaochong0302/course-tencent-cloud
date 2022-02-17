<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Deliver;

use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\ImGroupUser as ImGroupUserModel;
use App\Models\User as UserModel;
use App\Repos\ImGroup as ImGroupRepo;
use App\Repos\ImGroupUser as ImGroupUserRepo;
use App\Repos\ImUser as ImUserRepo;
use App\Services\Logic\Service as LogicService;

class CourseDeliver extends LogicService
{

    public function handle(CourseModel $course, UserModel $user)
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

        $groupRepo = new ImGroupRepo();

        $group = $groupRepo->findByCourseId($course->id);

        $imUserRepo = new ImUserRepo();

        $imUser = $imUserRepo->findById($user->id);

        $groupUserRepo = new ImGroupUserRepo();

        $groupUser = $groupUserRepo->findGroupUser($group->id, $user->id);

        if (!$groupUser) {

            $groupUser = new ImGroupUserModel();

            $groupUser->group_id = $group->id;
            $groupUser->user_id = $user->id;
            $groupUser->create();

            $imUser->group_count += 1;
            $imUser->update();

            $group->user_count += 1;
            $group->update();
        }
    }

}
