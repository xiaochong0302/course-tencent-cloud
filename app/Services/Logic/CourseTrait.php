<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic;

use App\Models\Course as CourseModel;
use App\Models\CourseUser as CourseUserModel;
use App\Models\User as UserModel;
use App\Repos\CourseUser as CourseUserRepo;
use App\Validators\Course as CourseValidator;

trait CourseTrait
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

    public function checkCourse($id)
    {
        $validator = new CourseValidator();

        return $validator->checkCourse($id);
    }

    public function checkCourseCache($id)
    {
        $validator = new CourseValidator();

        return $validator->checkCourseCache($id);
    }

    public function setCourseUser(CourseModel $course, UserModel $user)
    {
        if ($user->id == 0) return;

        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseUser($course->id, $user->id);

        $this->courseUser = $courseUser;

        if ($courseUser) {
            $this->joinedCourse = true;
        }

        if ($course->market_price == 0) {

            $this->ownedCourse = true;

        } elseif ($course->market_price > 0 && $course->vip_price == 0 && $user->vip == 1) {

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

}
