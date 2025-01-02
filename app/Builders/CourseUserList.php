<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Repos\Course as CourseRepo;

class CourseUserList extends Builder
{

    public function handleCourses($relations)
    {
        $courses = $this->getCourses($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['course'] = $courses[$value['course_id']] ?? null;
        }

        return $relations;
    }

    public function handleUsers($relations)
    {
        $users = $this->getUsers($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['user'] = $users[$value['user_id']] ?? null;
        }

        return $relations;
    }

    public function getCourses($relations)
    {
        $ids = kg_array_column($relations, 'course_id');

        $courseRepo = new CourseRepo();

        $columns = [
            'id', 'title', 'cover',
            'market_price', 'vip_price',
            'rating', 'model', 'level', 'attrs', 'published', 'deleted',
            'user_count', 'fake_user_count', 'lesson_count', 'review_count', 'favorite_count',
        ];

        $courses = $courseRepo->findByIds($ids, $columns);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($courses->toArray() as $course) {

            if ($course['fake_user_count'] > $course['user_count']) {
                $course['user_count'] = $course['fake_user_count'];
            }

            $course['cover'] = $baseUrl . $course['cover'];

            $course['attrs'] = json_decode($course['attrs'], true);

            $result[$course['id']] = $course;
        }

        return $result;
    }

    public function getUsers($relations)
    {
        $ids = kg_array_column($relations, 'user_id');

        return $this->getShallowUserByIds($ids);
    }

}
