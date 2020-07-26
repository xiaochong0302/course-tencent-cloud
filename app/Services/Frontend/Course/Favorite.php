<?php

namespace App\Services\Frontend\Course;

use App\Models\Course as CourseModel;
use App\Models\CourseFavorite as CourseFavoriteModel;
use App\Models\User as UserModel;
use App\Repos\CourseFavorite as CourseFavoriteRepo;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service as FrontendService;
use App\Validators\UserLimit as UserLimitValidator;

class Favorite extends FrontendService
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);

        $user = $this->getLoginUser();

        $validator = new UserLimitValidator();

        $validator->checkFavoriteLimit($user);

        $favoriteRepo = new CourseFavoriteRepo();

        $favorite = $favoriteRepo->findCourseFavorite($course->id, $user->id);

        if (!$favorite) {

            $favorite = new CourseFavoriteModel();

            $favorite->create([
                'course_id' => $course->id,
                'user_id' => $user->id,
            ]);

            $this->incrCourseFavoriteCount($course);

        } else {

            if ($favorite->deleted == 0) {

                $favorite->update(['deleted' => 1]);

                $this->decrCourseFavoriteCount($course);

                $this->decrUserFavoriteCount($user);

            } else {

                $favorite->update(['deleted' => 0]);

                $this->incrCourseFavoriteCount($course);

                $this->incrUserFavoriteCount($user);
            }
        }

        return $favorite;
    }

    protected function incrCourseFavoriteCount(CourseModel $course)
    {
        $course->favorite_count += 1;
        $course->update();
    }

    protected function decrCourseFavoriteCount(CourseModel $course)
    {
        if ($course->favorite_count > 0) {
            $course->favorite_count -= 1;
            $course->update();
        }
    }

    protected function incrUserFavoriteCount(UserModel $user)
    {
        $user->favorite_count += 1;
        $user->update();
    }

    protected function decrUserFavoriteCount(UserModel $user)
    {
        if ($user->favorite_count > 0) {
            $user->favorite_count -= 1;
            $user->update();
        }
    }

}
