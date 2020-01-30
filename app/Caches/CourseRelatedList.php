<?php

namespace App\Caches;

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
     * @param \App\Models\Course[] $courses
     * @return array
     */
    public function handleContent($courses)
    {
        $result = [];

        foreach ($courses as $course) {
            $result[] = [
                'id' => $course->id,
                'model' => $course->model,
                'title' => $course->title,
                'cover' => $course->cover,
                'summary' => $course->summary,
                'market_price' => $course->market_price,
                'vip_price' => $course->vip_price,
            ];
        }

        return $result;
    }

}
