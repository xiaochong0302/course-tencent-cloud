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

    /**
     * @param CourseModel $course
     * @param UserModel $user
     * @return array
     */
    protected function handleCourse($course, $user)
    {
        $result = $this->formatCourse($course);

        $me = [
            'joined' => false,
            'owned' => false,
            'reviewed' => false,
            'favorited' => false,
            'progress' => 0,
        ];

        if ($user->id > 0) {

            $favoriteRepo = new CourseFavoriteRepo();

            $favorite = $favoriteRepo->findCourseFavorite($course->id, $user->id);

            if ($favorite && $favorite->deleted == 0) {
                $me['favorited'] = true;
            }

            if ($this->courseUser) {
                $me['reviewed'] = $this->courseUser->reviewed;
                $me['progress'] = $this->courseUser->progress;
            }

            $me['joined'] = $this->joinedCourse;
            $me['owned'] = $this->ownedCourse;
        }

        $result['me'] = $me;

        return $result;
    }

    /**
     * @param CourseModel $course
     * @return array
     */
    protected function formatCourse($course)
    {
        $result = [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => kg_img_url($course->cover),
            'summary' => $course->summary,
            'details' => $course->details,
            'keywords' => $course->keywords,
            'market_price' => $course->market_price,
            'vip_price' => $course->vip_price,
            'study_expiry' => $course->study_expiry,
            'refund_expiry' => $course->refund_expiry,
            'score' => $course->score,
            'model' => $course->model,
            'level' => $course->level,
            'attrs' => $course->attrs,
            'user_count' => $course->user_count,
            'lesson_count' => $course->lesson_count,
            'review_count' => $course->review_count,
            'favorite_count' => $course->favorite_count,
        ];

        return $result;
    }

}
