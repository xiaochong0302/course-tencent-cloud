<?php

namespace App\Services\Frontend;

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
        $courseUser = null;

        if ($user->id > 0) {
            $courseUserRepo = new CourseUserRepo();
            $courseUser = $courseUserRepo->findCourseUser($course->id, $user->id);
        }

        $this->courseUser = $courseUser;

        if ($courseUser) {
            $this->joinedCourse = true;
        }

        if ($course->market_price == 0) {

            $this->ownedCourse = true;

        } elseif ($course->vip_price == 0 && $user->vip == 1) {

            $this->ownedCourse = true;

        } elseif ($courseUser) {

            $sourceTypes = [
                CourseUserModel::SOURCE_CHARGE,
                CourseUserModel::SOURCE_IMPORT,
            ];

            $caseA = $courseUser->deleted == 0;
            $caseB = $courseUser->expiry_time > time();
            $caseC = in_array($courseUser->source_type, $sourceTypes);

            if ($caseA && $caseB && $caseC) {
                $this->ownedCourse = true;
            }
        }
    }

}
