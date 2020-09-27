<?php

namespace App\Services\Logic\Course;

use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\CourseFavorite as CourseFavoriteRepo;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service;

class CourseInfo extends Service
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);

        $user = $this->getCurrentUser();

        $this->setCourseUser($course, $user);

        return $this->handleCourse($course, $user);
    }

    protected function handleCourse(CourseModel $course, UserModel $user)
    {
        $service = new BasicInfo();

        $result = $service->handleBasicInfo($course);

        $me = [
            'plan_id' => 0,
            'joined' => 0,
            'owned' => 0,
            'reviewed' => 0,
            'favorited' => 0,
            'progress' => 0,
        ];

        $me['joined'] = $this->joinedCourse ? 1 : 0;
        $me['owned'] = $this->ownedCourse ? 1 : 0;

        if ($user->id > 0) {

            $favoriteRepo = new CourseFavoriteRepo();

            $favorite = $favoriteRepo->findCourseFavorite($course->id, $user->id);

            if ($favorite && $favorite->deleted == 0) {
                $me['favorited'] = 1;
            }

            if ($this->courseUser) {
                $me['reviewed'] = $this->courseUser->reviewed ? 1 : 0;
                $me['progress'] = $this->courseUser->progress ? 1 : 0;
                $me['plan_id'] = $this->courseUser->plan_id;
            }
        }

        $result['me'] = $me;

        return $result;
    }

}
