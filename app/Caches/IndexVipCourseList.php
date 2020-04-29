<?php

namespace App\Caches;

use App\Models\Category as CategoryModel;
use App\Models\Course as CourseModel;
use App\Repos\User as UserRepo;
use App\Services\Category as CategoryService;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

/**
 * 会员特价课程
 *
 * Class IndexNewbieCourseList
 * @package App\Caches
 */
class IndexVipCourseList extends Cache
{

    protected $lifetime = 1 * 86400;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'index_vip_course_list';
    }

    public function getContent($id = null)
    {
        $result = [];

        $categoryLimit = 5;

        $courseLimit = 10;

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

            if ($courses->count() == 0) {
                continue;
            }

            $teacherMappings = $this->getTeacherMappings($courses);

            $categoryCourses = [];

            foreach ($courses as $course) {

                $teacher = $teacherMappings[$course->teacher_id];

                $categoryCourses[] = [
                    'id' => $course->id,
                    'title' => $course->title,
                    'cover' => $course->cover,
                    'teacher' => $teacher,
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
     * @param Resultset|CourseModel[] $courses
     * @return array
     */
    protected function getTeacherMappings($courses)
    {
        $teacherIds = kg_array_column($courses->toArray(), 'teacher_id');

        $userRepo = new UserRepo();

        $teachers = $userRepo->findByIds($teacherIds);

        $mappings = [];

        foreach ($teachers as $teacher) {
            $mappings[$teacher->id] = [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'avatar' => $teacher->avatar,
            ];
        }

        return $mappings;
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
            ->andWhere('vip_price >= 0')
            ->orderBy('score DESC')
            ->limit($limit)
            ->execute();
    }

}
