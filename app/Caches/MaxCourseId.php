<?php

namespace App\Caches;

use App\Models\Course as CourseModel;

class MaxCourseId extends Cache
{

    protected $lifetime = 365 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'max_id:course';
    }

    public function getContent($id = null)
    {
        $course = CourseModel::findFirst(['order' => 'id DESC']);

        return $course->id ?? 0;
    }

}
