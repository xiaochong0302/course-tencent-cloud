<?php

namespace App\Builders;

use App\Caches\CategoryList as CategoryListCache;
use App\Repos\User as UserRepo;

class CourseList extends Builder
{

    public function handleCategories($courses)
    {
        $categories = $this->getCategories();

        foreach ($courses as $key => $course) {
            $courses[$key]['category'] = $categories[$course['category_id']] ?? [];
        }

        return $courses;
    }

    public function handleTeachers($courses)
    {
        $teachers = $this->getTeachers($courses);

        foreach ($courses as $key => $course) {
            $courses[$key]['teacher'] = $teachers[$course['teacher_id']] ?? [];
        }

        return $courses;
    }

    protected function getCategories()
    {
        $cache = new CategoryListCache();

        $items = $cache->get();

        if (!$items) return null;

        $result = [];

        foreach ($items as $item) {
            $result[$item['id']] = [
                'id' => $item['id'],
                'name' => $item['name'],
            ];
        }

        return $result;
    }

    protected function getTeachers($courses)
    {
        $ids = kg_array_column($courses, 'teacher_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar']);

        $baseUrl = kg_ci_base_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
