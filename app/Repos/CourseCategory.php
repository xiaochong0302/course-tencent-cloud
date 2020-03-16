<?php

namespace App\Repos;

use App\Models\CourseCategory as CourseCategoryModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CourseCategory extends Repository
{

    /**
     * @param int $courseId
     * @param int $categoryId
     * @return CourseCategoryModel|Model|bool
     */
    public function findCourseCategory($courseId, $categoryId)
    {
        $result = CourseCategoryModel::findFirst([
            'conditions' => 'course_id = :course_id: AND category_id = :category_id:',
            'bind' => ['course_id' => $courseId, 'category_id' => $categoryId],
        ]);

        return $result;
    }

    /**
     * @param array $categoryIds
     * @return ResultsetInterface|Resultset|CourseCategoryModel[]
     */
    public function findByCategoryIds($categoryIds)
    {
        $result = CourseCategoryModel::query()
            ->inWhere('category_id', $categoryIds)
            ->execute();

        return $result;
    }

    /**
     * @param array $courseIds
     * @return ResultsetInterface|Resultset|CourseCategoryModel[]
     */
    public function findByCourseIds($courseIds)
    {
        $result = CourseCategoryModel::query()
            ->inWhere('course_id', $courseIds)
            ->execute();

        return $result;
    }

}
