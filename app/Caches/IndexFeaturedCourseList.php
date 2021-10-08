<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Category as CategoryModel;
use App\Models\Course as CourseModel;
use App\Services\Category as CategoryService;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

/**
 * 推荐课程
 */
class IndexFeaturedCourseList extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'index_featured_course_list';
    }

    public function getContent($id = null)
    {
        $categoryLimit = 5;

        $courseLimit = 8;

        $categories = $this->findCategories($categoryLimit);

        if ($categories->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($categories as $category) {

            $item = [];

            $item['category'] = [
                'id' => $category->id,
                'name' => $category->name,
            ];

            $item['courses'] = [];

            $courses = $this->findCategoryCourses($category->id, $courseLimit);

            if ($courses->count() == 0) {
                continue;
            }

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

            $item['courses'] = $categoryCourses;

            $result[] = $item;
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
            ->where('type = :type:', ['type' => CategoryModel::TYPE_COURSE])
            ->andWhere('level = 1')
            ->andWhere('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('priority ASC')
            ->limit($limit)
            ->execute();
    }

    /**
     * @param int $categoryId
     * @param int $limit
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    protected function findCategoryCourses($categoryId, $limit = 8)
    {
        $categoryService = new CategoryService();

        $categoryIds = $categoryService->getChildCategoryIds($categoryId);

        return CourseModel::query()
            ->inWhere('category_id', $categoryIds)
            ->andWhere('featured = 1')
            ->andWhere('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('id DESC')
            ->limit($limit)
            ->execute();
    }

}
