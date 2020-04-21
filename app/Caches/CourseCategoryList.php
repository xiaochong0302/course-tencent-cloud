<?php

namespace App\Caches;

use App\Models\Category as CategoryModel;
use App\Repos\Course as CourseRepo;

class CourseCategoryList extends Cache
{

    protected $lifetime = 1 * 86400;

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
