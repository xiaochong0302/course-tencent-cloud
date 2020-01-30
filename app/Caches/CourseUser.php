<?php

namespace App\Caches;

use App\Repos\CourseUser as CourseUserRepo;

class CourseUser extends Cache
{

    protected $lifetime = 7 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    /**
     * id = {$courseId}_{$userId}
     *
     * @param string $id
     * @return string
     */
    public function getKey($id = null)
    {
        return "course_user:{$id}";
    }

    public function getContent($id = null)
    {
        list($courseId, $userId) = explode('_', $id);

        $courseUserRepo = new CourseUserRepo();

        $courseUser = $courseUserRepo->findCourseUser($courseId, $userId);

        if (!$courseUser) {
            return new \stdClass();
        }

        return $courseUser;
    }

}
