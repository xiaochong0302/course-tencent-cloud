<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Category as CategoryModel;
use App\Repos\Course as CourseRepo;

class CourseCategoryList extends Cache
{

    protected $lifetime = 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return "course_category_list:{$id}";
    }

    public function getContent($id = null)
    {
        $courseRepo = new CourseRepo();

        $categories = $courseRepo->findCategories($id);

        if ($categories->count() == 0) {
            return [];
        }

        return $this->handleContent($categories);
    }

    /**
     * @param CategoryModel[] $categories
     * @return array
     */
    public function handleContent($categories)
    {
        $result = [];

        foreach ($categories as $category) {
            $result[] = [
                'id' => $category->id,
                'name' => $category->name,
            ];
        }

        return $result;
    }

}
