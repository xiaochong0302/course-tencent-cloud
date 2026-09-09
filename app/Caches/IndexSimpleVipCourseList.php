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

class IndexSimpleVipCourseList extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 3600;

    /**
     * @var int
     */
    protected int $limit = 8;

    public function getKey($id = null): string
    {
        return 'index-simple-vip-course-list';
    }

    public function getContent($id = null): array
    {
        $courses = $this->findCourses($this->limit);

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
    protected function findCourses(int $limit = 8)
    {
        return CourseModel::query()
            ->where('market_price > vip_price')
            ->andWhere('vip_price >= 0')
            ->andWhere('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('score DESC')
            ->limit($limit)
            ->execute();
    }

}
