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

class CourseRecommendedList extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "course_recommended_list:{$id}";
    }

    public function getContent($id = null)
    {
        $courses = $this->findCourses(5);

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

            $userCount = $course->user_count;

            if ($course->fake_user_count > $course->user_count) {
                $userCount = $course->fake_user_count;
            }

            $result[] = [
                'id' => $course->id,
                'title' => $course->title,
                'cover' => $course->cover,
                'market_price' => $course->market_price,
                'vip_price' => $course->vip_price,
                'model' => $course->model,
                'level' => $course->level,
                'user_count' => $userCount,
                'lesson_count' => $course->lesson_count,
            ];
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    public function findCourses($limit = 5)
    {
        return CourseModel::query()
            ->where('market_price > 0')
            ->andWhere('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('RAND()')
            ->limit($limit)
            ->execute();
    }

}
