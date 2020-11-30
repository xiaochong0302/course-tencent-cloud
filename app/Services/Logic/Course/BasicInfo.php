<?php

namespace App\Services\Logic\Course;

use App\Caches\CourseTeacherList as CourseTeacherListCache;
use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service;

class BasicInfo extends Service
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);

        return $this->handleBasicInfo($course);
    }

    public function handleBasicInfo(CourseModel $course)
    {
        $course->details = kg_parse_markdown($course->details);

        $teachers = $this->handleTeachers($course);
        $ratings = $this->handleRatings($course);

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
            'teachers' => $teachers,
            'ratings' => $ratings,
            'model' => $course->model,
            'level' => $course->level,
            'attrs' => $course->attrs,
            'user_count' => $course->user_count,
            'lesson_count' => $course->lesson_count,
            'resource_count' => $course->resource_count,
            'package_count' => $course->package_count,
            'review_count' => $course->review_count,
            'consult_count' => $course->consult_count,
            'favorite_count' => $course->favorite_count,
        ];
    }

    protected function handleRatings(CourseModel $course)
    {
        $repo = new CourseRepo();

        $rating = $repo->findCourseRating($course->id);

        return [
            'rating' => $rating->rating,
            'rating1' => $rating->rating1,
            'rating2' => $rating->rating2,
            'rating3' => $rating->rating3,
        ];
    }

    protected function handleTeachers(CourseModel $course)
    {
        $cache = new CourseTeacherListCache();

        $result = $cache->get($course->id);

        return $result ?: [];
    }

}
