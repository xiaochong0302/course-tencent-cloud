<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Caches\CategoryList as CategoryListCache;
use App\Models\Category as CategoryModel;
use App\Repos\User as UserRepo;

class CourseList extends Builder
{

    public function handleCategories(array $courses)
    {
        $categories = $this->getCategories();

        foreach ($courses as $key => $course) {
            $courses[$key]['category'] = $categories[$course['category_id']] ?? new \stdClass();
        }

        return $courses;
    }

    public function handleTeachers(array $courses)
    {
        $teachers = $this->getTeachers($courses);

        foreach ($courses as $key => $course) {
            $courses[$key]['teacher'] = $teachers[$course['teacher_id']] ?? new \stdClass();
        }

        return $courses;
    }

    public function getCategories()
    {
        $cache = new CategoryListCache();

        $items = $cache->get(CategoryModel::TYPE_COURSE);

        if (empty($items)) return [];

        $result = [];

        foreach ($items as $item) {
            $result[$item['id']] = [
                'id' => $item['id'],
                'name' => $item['name'],
            ];
        }

        return $result;
    }

    public function getTeachers($courses)
    {
        $ids = kg_array_column($courses, 'teacher_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar']);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
