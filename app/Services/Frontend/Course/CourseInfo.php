<?php

namespace App\Services\Frontend\Course;

use App\Models\Course as CourseModel;
use App\Models\User as UserModel;
use App\Repos\CourseFavorite as CourseFavoriteRepo;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service;

class CourseInfo extends Service
{

    use CourseTrait;

    public function getCourse($id)
    {
        $course = $this->checkCourse($id);

        $user = $this->getCurrentUser();

        $this->setCourseUser($course, $user);

        return $this->handleCourse($course, $user);
    }

    protected function handleCourse(CourseModel $course, UserModel $user)
    {
        $result = $this->formatCourse($course);

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

    protected function formatCourse($course)
    {
        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => kg_ci_img_url($course->cover),
            'summary' => $course->summary,
            'details' => $course->details,
            'keywords' => $course->keywords,
            'market_price' => (float)$course->market_price,
            'vip_price' => (float)$course->vip_price,
            'study_expiry' => $course->study_expiry,
            'refund_expiry' => $course->refund_expiry,
            'rating' => (float)$course->rating,
            'score' => (float)$course->score,
            'model' => $course->model,
            'level' => $course->level,
            'attrs' => $course->attrs,
            'user_count' => $course->user_count,
            'lesson_count' => $course->lesson_count,
            'review_count' => $course->review_count,
            'favorite_count' => $course->favorite_count,
        ];
    }

}
