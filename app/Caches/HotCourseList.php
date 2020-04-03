<?php

namespace App\Caches;

use App\Models\Course as CourseModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class HotCourseList extends Cache
{

    protected $lifetime = 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'hot_course_list';
    }

    public function getContent($id = null)
    {
        $courses = $this->findHotCourses($id);

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

        foreach ($courses as $course) {
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
    protected function findHotCourses($limit = 10)
    {
        return CourseModel::query()
            ->where('deleted = 0')
            ->orderBy('score DESC')
            ->limit($limit)
            ->execute();
    }

}
