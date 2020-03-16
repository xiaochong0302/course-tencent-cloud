<?php

namespace App\Caches;

use App\Repos\Course as CourseRepo;

class Course extends Cache
{

    protected $lifetime = 7 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "course:{$id}";
    }

    public function getContent($id = null)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($id);

        $course->cover = kg_img_url($course->cover);

        if (!$course) {
            return new \stdClass();
        }

        return $course;
    }

}
