<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Course;

use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;
use App\Services\Category as CategoryService;
use App\Services\Logic\CourseTrait;
use App\Services\Logic\Service as LogicService;
use App\Services\Logic\User\ShallowUserInfo as ShallowUserInfoService;

class BasicInfo extends LogicService
{

    use CourseTrait;

    public function handle($id)
    {
        $course = $this->checkCourse($id);

        return $this->handleBasicInfo($course);
    }

    public function handleBasicInfo(CourseModel $course)
    {
        $categoryPaths = $this->handleCategoryPaths($course->category_id);
        $teacher = $this->handleTeacherInfo($course->teacher_id);
        $ratings = $this->handleRatings($course);

        return [
            'id' => $course->id,
            'title' => $course->title,
            'cover' => $course->cover,
            'summary' => $course->summary,
            'details' => $course->details,
            'keywords' => $course->keywords,
            'market_price' => (float)$course->market_price,
            'vip_price' => (float)$course->vip_price,
            'study_expiry' => $course->study_expiry,
            'refund_expiry' => $course->refund_expiry,
            'category_paths' => $categoryPaths,
            'teacher' => $teacher,
            'ratings' => $ratings,
            'model' => $course->model,
            'level' => $course->level,
            'attrs' => $course->attrs,
            'published' => $course->published,
            'deleted' => $course->deleted,
            'user_count' => $course->getUserCount(),
            'lesson_count' => $course->lesson_count,
            'resource_count' => $course->resource_count,
            'package_count' => $course->package_count,
            'review_count' => $course->review_count,
            'consult_count' => $course->consult_count,
            'favorite_count' => $course->favorite_count,
            'create_time' => $course->create_time,
            'update_time' => $course->update_time,
        ];
    }

    protected function handleRatings(CourseModel $course)
    {
        $repo = new CourseRepo();

        $rating = $repo->findCourseRating($course->id);

        return [
            'rating' => round($rating->rating, 1),
            'rating1' => round($rating->rating1, 1),
            'rating2' => round($rating->rating2, 1),
            'rating3' => round($rating->rating3, 1),
        ];
    }

    protected function handleCategoryPaths($categoryId)
    {
        if ($categoryId == 0) return null;

        $service = new CategoryService();

        return $service->getCategoryPaths($categoryId);
    }

    protected function handleTeacherInfo($userId)
    {
        if ($userId == 0) return null;

        $service = new ShallowUserInfoService();

        return $service->handle($userId);
    }

}
