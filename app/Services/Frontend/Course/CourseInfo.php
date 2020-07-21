<?php

namespace App\Services\Frontend\Course;

use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\CourseFavorite as CourseFavoriteRepo;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service as FrontendService;

class CourseInfo extends FrontendService
{

    use CourseTrait;
    use CourseBasicInfoTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);

        $user = $this->getCurrentUser();

        $this->setCourseUser($course, $user);

        return $this->handleCourse($course, $user);
    }

    protected function handleCourse(CourseModel $course, UserModel $user)
    {
        $result = $this->handleBasicInfo($course);

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
