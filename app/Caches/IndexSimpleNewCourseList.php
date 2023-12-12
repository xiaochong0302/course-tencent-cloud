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
 * 简版新上课程
 */
class IndexSimpleNewCourseList extends Cache
{

    protected $lifetime = 3600;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'index_simple_new_course_list';
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

            $userCount = $course->user_count;

            if ($course->fake_user_count > $course->user_count) {
                $userCount = $course->fake_user_count;
            }

            $result[] = [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'model' => $course->model,
                'level' => $course->level,
                'rating' => round($course->rating, 1),
                'market_price' => (float)$course->market_price,
                'vip_price' => (float)$course->vip_price,
                'user_count' => $userCount,
                'lesson_count' => $course->lesson_count,
                'review_count' => $course->review_count,
                'favorite_count' => $course->favorite_count,
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
            ->where('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('id DESC')
            ->limit($limit)
            ->execute();
    }

}
