<?php

namespace App\Caches;

use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;

class CourseRelatedList extends Cache
{

    protected $lifetime = 7 * 86400;

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

        $imgBaseUrl = kg_img_base_url();

        foreach ($courses as $course) {

            $course->cover = $imgBaseUrl . $course->cover;

            $result[] = [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'summary' => $course->summary,
                'market_price' => (float)$course->market_price,
                'vip_price' => (float)$course->vip_price,
                'model' => $course->model,
                'level' => $course->level,
            ];
        }

        return $result;
    }

}
