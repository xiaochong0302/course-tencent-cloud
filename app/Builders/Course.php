<?php

namespace App\Builders;

use App\Models\Course as CourseModel;

class Course extends Builder
{

    /**
     * @param CourseModel $course
     * @return CourseModel
     */
    public function handleCourse(CourseModel $course)
    {
        $course->cover = kg_img_url($course->cover);

        return $course;
    }

}
