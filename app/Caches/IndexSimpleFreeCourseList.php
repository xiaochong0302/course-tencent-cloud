<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Course as CourseModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

/**
 * 简版免费课程
 */
class IndexSimpleFreeCourseList extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'index_simple_free_course_list';
    }

    public function getContent($id = null)
    {
        $limit = 8;

        $courses = $this->findCourses($limit);

        if ($courses->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($courses as $course) {
            $result[] = [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'market_price' => $course->market_price,
                'vip_price' => $course->vip_price,
                'model' => $course->model,
                'level' => $course->level,
                'user_count' => $course->user_count,
                'lesson_count' => $course->lesson_count,
            ];
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    protected function findCourses($limit = 8)
    {
        return CourseModel::query()
            ->where('market_price = 0')
            ->andWhere('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('score DESC')
            ->limit($limit)
            ->execute();
    }

}
