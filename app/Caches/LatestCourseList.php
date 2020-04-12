<?php

namespace App\Caches;

use App\Models\Course as CourseModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

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
        $courses = $this->findLatestCourses(5);

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

        $baseUrl = kg_ci_base_url();

        foreach ($courses as $course) {

            $course->cover = $baseUrl . $course->cover;

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

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    protected function findLatestCourses($limit = 10)
    {
        return CourseModel::query()
            ->where('deleted = 0')
            ->orderBy('create_time DESC')
            ->limit($limit)
            ->execute();
    }

}
