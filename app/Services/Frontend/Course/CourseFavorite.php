<?php

namespace App\Services\Frontend\Course;

use App\Models\Course as CourseModel;
use App\Models\CourseFavorite as FavoriteModel;
use App\Models\User as UserModel;
use App\Repos\CourseFavorite as CourseFavoriteRepo;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service;
use App\Validators\UserDailyLimit as UserDailyLimitValidator;

class CourseFavorite extends Service
{

    use CourseTrait;

    public function saveFavorite($id)
    {
        $course = $this->checkCourse($id);

        $user = $this->getLoginUser();

        $validator = new UserDailyLimitValidator();

        $validator->checkFavoriteLimit($user);

        $favoriteRepo = new CourseFavoriteRepo();

        $favorite = $favoriteRepo->findCourseFavorite($course->id, $user->id);

        if (!$favorite) {

            $favorite = new FavoriteModel();

            $favorite->course_id = $course->id;
            $favorite->user_id = $user->id;

            $favorite->create();

            $this->incrCourseFavoriteCount($course);

        } else {

            if ($favorite->deleted == 0) {

                $favorite->deleted = 1;

                $this->decrCourseFavoriteCount($course);

            } else {

                $favorite->deleted = 0;

                $this->incrCourseFavoriteCount($course);
            }

            $favorite->update();
        }

        $this->incrUserDailyFavoriteCount($user);
    }

    protected function incrCourseFavoriteCount(CourseModel $course)
    {
        $this->eventsManager->fire('courseCounter:incrFavoriteCount', $this, $course);
    }

    protected function decrCourseFavoriteCount(CourseModel $course)
    {
        $this->eventsManager->fire('courseCounter:decrFavoriteCount', $this, $course);
    }

    protected function incrUserDailyFavoriteCount(UserModel $user)
    {
        $this->eventsManager->fire('userDailyCounter:incrFavoriteCount', $this, $user);
    }

}
