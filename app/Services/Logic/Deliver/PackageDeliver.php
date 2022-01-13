<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Deliver;

use App\Models\CourseUser as CourseUserModel;
use App\Models\ImGroupUser as ImGroupUserModel;
use App\Models\Package as PackageModel;
use App\Models\User as UserModel;
use App\Repos\ImGroup as ImGroupRepo;
use App\Repos\ImGroupUser as ImGroupUserRepo;
use App\Repos\ImUser as ImUserRepo;
use App\Repos\Package as PackageRepo;
use App\Services\Logic\Service as LogicService;

class PackageDeliver extends LogicService
{

    public function handle(PackageModel $package, UserModel $user)
    {
        $packageRepo = new PackageRepo();

        $courses = $packageRepo->findCourses($package->id);

        foreach ($courses as $course) {

            $courseUser = new CourseUserModel();

            $courseUser->user_id = $user->id;
            $courseUser->course_id = $course->id;
            $courseUser->expiry_time = strtotime("+{$course->study_expiry} months");
            $courseUser->role_type = CourseUserModel::ROLE_STUDENT;
            $courseUser->source_type = CourseUserModel::SOURCE_CHARGE;
            $courseUser->create();

            $course->user_count += 1;
            $course->update();

            $imUserRepo = new ImUserRepo();

            $imUser = $imUserRepo->findById($user->id);

            $groupRepo = new ImGroupRepo();

            $group = $groupRepo->findByCourseId($course->id);

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

}
