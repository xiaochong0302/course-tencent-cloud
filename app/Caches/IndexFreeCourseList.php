<?php

namespace App\Caches;

use App\Models\Category as CategoryModel;
use App\Models\Course as CourseModel;
use App\Services\Category as CategoryService;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

/**
 * 免费课程
 *
 * Class IndexNewbieCourseList
 * @package App\Caches
 */
class IndexFreeCourseList extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'index_free_course_list';
    }

    public function getContent($id = null)
    {
        $result = [];

        $categoryLimit = 5;

        $courseLimit = 5;

        $categories = $this->findCategories($categoryLimit);

        if ($categories->count() == 0) {
            return null;
        }

        foreach ($categories as $category) {

            $categoryItem = [
                'id' => $category->id,
                'name' => $category->name,
            ];

            $courses = $this->findCategoryCourses($category->id, $courseLimit);

            if ($courses->count() == 0) continue;

            $categoryCourses = [];

            foreach ($courses as $course) {
                $categoryCourses[] = [
                    'id' => $course->id,
                    'title' => $course->title,
                    'cover' => $course->cover,
                    'market_price' => $course->market_price,
                    'vip_price' => $course->vip_price,
                    'model' => $course->model,
                    'level' => $course->level,
                    'user_count' => $course->user_count,
                    'lesson_count' => $course->lesson_count,
                ];
            }

            $categoryItem['courses'] = $categoryCourses;

            $result[] = $categoryItem;
        }

        return $result;
    }

    /**
     * @param int $limit
     * @return ResultsetInterface|Resultset|CategoryModel[]
     */
    protected function findCategories($limit = 5)
    {
        return CategoryModel::query()
            ->andWhere('published = 1')
            ->orderBy('priority ASC')
            ->limit($limit)
            ->execute();
    }

    /**
     * @param int $categoryId
     * @param int $limit
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    protected function findCategoryCourses($categoryId, $limit = 10)
    {
        $categoryService = new CategoryService();

        $categoryIds = $categoryService->getChildCategoryIds($categoryId);

        return CourseModel::query()
            ->inWhere('category_id', $categoryIds)
            ->andWhere('published = 1')
            ->andWhere('market_price = 0')
            ->orderBy('id DESC')
            ->limit($limit)
            ->execute();
    }

}
