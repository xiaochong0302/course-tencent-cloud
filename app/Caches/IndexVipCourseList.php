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

class IndexVipCourseList extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 3600;

    /**
     * @var int
     */
    protected int $categoryLimit = 5;

    /**
     * @var int
     */
    protected int $courseLimit = 8;

    public function getKey($id = null): string
    {
        return 'index-vip-course-list';
    }

    public function getContent($id = null): array
    {
        $categories = $this->findCategories($this->categoryLimit);

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

            $courses = $this->findCategoryCourses($category->id, $this->courseLimit);

            if ($courses->count() == 0) {
                continue;
            }

            $categoryCourses = [];

            foreach ($courses as $course) {

                $userCount = $course->user_count;

                if ($course->fake_user_count > $course->user_count) {
                    $userCount = $course->fake_user_count;
                }

                $categoryCourses[] = [
                    'id' => $course->id,
                    'title' => $course->title,
                    'cover' => $course->cover,
                    'model' => $course->model,
                    'level' => $course->level,
                    'rating' => round($course->rating, 1),
                    'market_price' => (float)$course->market_price,
                    'vip_price' => (float)$course->vip_price,
                    'user_count' => $userCount,
                    'lesson_count' => $course->lesson_count,
                    'review_count' => $course->review_count,
                    'favorite_count' => $course->favorite_count,
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
    protected function findCategories(int $limit = 5)
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
    protected function findCategoryCourses(int $categoryId, int $limit = 8)
    {
        $categoryService = new CategoryService();

        $childCategoryIds = $categoryService->getChildCategoryIds($categoryId);

        $categoryIds = array_merge([$categoryId], $childCategoryIds);

        return CourseModel::query()
            ->inWhere('category_id', $categoryIds)
            ->andWhere('market_price > vip_price')
            ->andWhere('vip_price >= 0')
            ->andWhere('published = 1')
            ->andWhere('deleted = 0')
            ->orderBy('score DESC')
            ->limit($limit)
            ->execute();
    }

}
