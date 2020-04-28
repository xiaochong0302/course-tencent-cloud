<?php

namespace App\Caches;

use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;

class CourseRelatedList extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "course_related_list:{$id}";
    }

    public function getContent($id = null)
    {
        $courseRepo = new CourseRepo();

        $courses = $courseRepo->findRelatedCourses($id);

        if ($courses->count() == 0) {
            return [];
        }

        return $this->handleContent($courses);
    }

    /**
     * @param CourseModel[] $courses
     * @return array
     */
    public function handleContent($courses)
    {
        $result = [];

        foreach ($courses as $course) {

            $result[] = [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'market_price' => $course->market_price,
                'vip_price' => $course->vip_price,
                'rating' => $course->rating,
                'model' => $course->model,
                'level' => $course->level,
                'user_count' => $course->user_count,
                'lesson_count' => $course->lesson_count,
            ];
        }

        return $result;
    }

}
