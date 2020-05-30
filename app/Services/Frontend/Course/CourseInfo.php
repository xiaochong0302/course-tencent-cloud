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

    public function handle($id)
    {
        $course = $this->checkCourseCache($id);

        $user = $this->getCurrentUser();

        $this->setCourseUser($course, $user);

        return $this->handleCourse($course, $user);
    }

    protected function handleCourse(CourseModel $course, UserModel $user)
    {
        $result = [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
            'summary' => $course->summary,
            'details' => $course->details,
            'keywords' => $course->keywords,
            'category_id' => $course->category_id,
            'teacher_id' => $course->teacher_id,
            'market_price' => $course->market_price,
            'vip_price' => $course->vip_price,
            'study_expiry' => $course->study_expiry,
            'refund_expiry' => $course->refund_expiry,
            'rating' => $course->rating,
            'model' => $course->model,
            'level' => $course->level,
            'attrs' => $course->attrs,
            'user_count' => $course->user_count,
            'lesson_count' => $course->lesson_count,
            'review_count' => $course->review_count,
            'comment_count' => $course->comment_count,
            'consult_count' => $course->consult_count,
            'favorite_count' => $course->favorite_count,
        ];

        $me = [
            'joined' => 0,
            'owned' => 0,
            'reviewed' => 0,
            'favorited' => 0,
            'progress' => 0,
        ];

        if ($user->id > 0) {

            $favoriteRepo = new CourseFavoriteRepo();

            $favorite = $favoriteRepo->findCourseFavorite($course->id, $user->id);

            if ($favorite && $favorite->deleted == 0) {
                $me['favorited'] = 1;
            }

            if ($this->courseUser) {
                $me['reviewed'] = $this->courseUser->reviewed ? 1 : 0;
                $me['progress'] = $this->courseUser->progress ? 1 : 0;
            }

            $me['joined'] = $this->joinedCourse ? 1 : 0;
            $me['owned'] = $this->ownedCourse ? 1 : 0;
        }

        $result['me'] = $me;

        return $result;
    }

}
