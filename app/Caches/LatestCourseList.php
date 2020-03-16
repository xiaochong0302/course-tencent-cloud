<?php

namespace App\Caches;

use App\Models\Course as CourseModel;
use App\Repos\Course as CourseRepo;
use Phalcon\Mvc\Model\Resultset;

class LatestCourseList extends Cache
{

    protected $lifetime = 7 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'latest_course_list';
    }

    public function getContent($id = null)
    {
        $courseRepo = new CourseRepo();

        /**
         * @var Resultset $courses
         */
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
                'summary' => $course->summary,
                'cover' => $course->cover,
                'market_price' => $course->market_price,
                'vip_price' => $course->vip_price,
                'model' => $course->model,
                'level' => $course->level,
            ];
        }

        return $result;
    }

}
