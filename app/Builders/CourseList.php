<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

use App\Caches\CategoryAllList as CategoryAllListCache;
use App\Models\Category as CategoryModel;

class CourseList extends Builder
{

    public function handleCategories(array $courses)
    {
        $categories = $this->getCategories();

        foreach ($courses as $key => $course) {
            $courses[$key]['category'] = $categories[$course['category_id']] ?? null;
        }

        return $courses;
    }

    public function handleTeachers(array $courses)
    {
        $teachers = $this->getTeachers($courses);

        foreach ($courses as $key => $course) {
            $courses[$key]['teacher'] = $teachers[$course['teacher_id']] ?? null;
        }

        return $courses;
    }

    public function getCategories()
    {
        $cache = new CategoryAllListCache();

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

        return $this->getShallowUserByIds($ids);
    }

}
