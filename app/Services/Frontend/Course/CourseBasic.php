<?php

namespace App\Services\Frontend\Course;

use App\Models\Course as CourseModel;
use App\Services\Frontend\CourseTrait;
use App\Services\Frontend\Service as FrontendService;

class CourseBasic extends FrontendService
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourseCache($id);

        return $this->handleCourse($course);
    }

    protected function handleCourse(CourseModel $course)
    {
        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
            'summary' => $course->summary,
            'details' => $course->details,
            'keywords' => $course->keywords,
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
            'package_count' => $course->package_count,
            'review_count' => $course->review_count,
            'comment_count' => $course->comment_count,
            'consult_count' => $course->consult_count,
            'favorite_count' => $course->favorite_count,
        ];
    }

}
