<?php

namespace App\Caches;

use App\Repos\Course as CourseRepo;

class CourseCounter extends Counter
{

    protected $lifetime = 7 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "course_counter:{$id}";
    }

    public function getContent($id = null)
    {
        $courseRepo = new CourseRepo();

        $course = $courseRepo->findById($id);

        return [
            'user_count' => $course->user_count,
            'lesson_count' => $course->lesson_count,
            'comment_count' => $course->comment_count,
            'consult_count' => $course->consult_count,
            'review_count' => $course->review_count,
            'favorite_count' => $course->favorite_count,
        ];
    }

}
