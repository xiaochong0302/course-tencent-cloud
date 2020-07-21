<?php

namespace App\Services\Frontend\Course;

use App\Caches\CourseTeacherList as CourseTeacherListCache;
use App\Http\Web\Services\CourseQuery as CourseQueryService;
use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;

trait CourseBasicInfoTrait
{

    protected function handleBasicInfo(CourseModel $course)
    {
        $categoryPaths = $this->handleCategoryPaths($course);

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
            'category_paths' => $categoryPaths,
            'teachers' => $teachers,
            'ratings' => $ratings,
            'model' => $course->model,
            'level' => $course->level,
            'attrs' => $course->attrs,
            'user_count' => $course->user_count,
            'lesson_count' => $course->lesson_count,
            'package_count' => $course->package_count,
            'review_count' => $course->review_count,
            'consult_count' => $course->consult_count,
            'favorite_count' => $course->favorite_count,
        ];
    }

    protected function handleCategoryPaths(CourseModel $course)
    {
        $service = new CourseQueryService();

        return $service->handleCategoryPaths($course->category_id);
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
