<?php

namespace App\Services;

use App\Repos\Category as CategoryRepo;
use App\Repos\CourseCategory as CourseCategoryRepo;

class Category extends Service
{

    /**
     * 通过多个子级分类查找课程号
     *
     * @param mixed $categoryIds
     * @return array
     */
    public function getCourseIdsByMultiCategory($categoryIds)
    {
        $courseCategoryRepo = new CourseCategoryRepo();

        if (!is_array($categoryIds)) {
            $categoryIds = explode(',', $categoryIds);
        }

        $relations = $courseCategoryRepo->findByCategoryIds($categoryIds);

        $result = [];

        if ($relations->count() > 0) {
            foreach ($relations as $relation) {
                $result[] = $relation->course_id;
            }
        }

        return $result;
    }

    /**
     * 通过单个分类（顶级|子级）查找课程号
     *
     * @param integer $categoryId
     * @return array
     */
    public function getCourseIdsBySingleCategory($categoryId)
    {
        $categoryRepo = new CategoryRepo();

        $category = $categoryRepo->findById($categoryId);

        $childCategoryIds = [];

        if ($category->level == 1) {
            $childCategories = $categoryRepo->findChildCategories($categoryId);
            if ($childCategories->count() > 0) {
                foreach ($childCategories as $category) {
                    $childCategoryIds[] = $category->id;
                }
            }
        } else {
            $childCategoryIds[] = $categoryId;
        }

        if (empty($childCategoryIds)) {
            return [];
        }

        $courseCategoryRepo = new CourseCategoryRepo();

        $relations = $courseCategoryRepo->findByCategoryIds($childCategoryIds);

        $result = [];

        if ($relations->count() > 0) {
            foreach ($relations as $relation) {
                $result[] = $relation->course_id;
            }
        }

        return $result;
    }

}
