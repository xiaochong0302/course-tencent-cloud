<?php

namespace App\Caches;

use App\Repos\CourseUser as CourseUserRepo;

class CourseUser extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "course_user:{$id}";
    }

    public function getContent($id = null)
    {
        list($courseId, $userId) = explode('_', $id);

        $repo = new CourseUserRepo();

        $courseUser = $repo->findCourseUser($courseId, $userId);

        return $courseUser ?: null;
    }

}
