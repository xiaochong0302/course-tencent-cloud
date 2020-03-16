<?php

namespace App\Builders;

use App\Repos\Course as CourseRepo;
use App\Repos\User as UserRepo;

class CourseFavoriteList extends Builder
{

    public function handleCourses($relations)
    {
        $courses = $this->getCourses($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['course'] = $courses[$value['course_id']];
        }

        return $relations;
    }

    public function handleUsers($relations)
    {
        $users = $this->getUsers($relations);

        foreach ($relations as $key => $value) {
            $relations[$key]['user'] = $users[$value['user_id']];
        }

        return $relations;
    }

    public function getCourses($relations)
    {
        $ids = kg_array_column($relations, 'course_id');

        $courseRepo = new CourseRepo();

        $columns = [
            'id', 'title', 'cover', 'summary',
            'market_price', 'vip_price', 'model', 'level', 'attrs',
            'user_count', 'lesson_count', 'review_count', 'favorite_count',
        ];

        $courses = $courseRepo->findByIds($ids, $columns);

        $imgBaseUrl = kg_img_base_url();

        $result = [];

        foreach ($courses->toArray() as $course) {
            $course['cover'] = $imgBaseUrl . $course['cover'];
            $course['attrs'] = json_decode($course['attrs'], true);
            $result[$course['id']] = $course;
        }

        return $result;
    }

    public function getUsers($relations)
    {
        $ids = kg_array_column($relations, 'user_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar']);

        $imgBaseUrl = kg_img_base_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $imgBaseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
