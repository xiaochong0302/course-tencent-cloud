<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
        return CourseCategoryModel::findFirst([
            'conditions' => 'course_id = :course_id: AND category_id = :category_id:',
            'bind' => ['course_id' => $courseId, 'category_id' => $categoryId],
        ]);
    }

    /**
     * @param array $categoryIds
     * @return ResultsetInterface|Resultset|CourseCategoryModel[]
     */
    public function findByCategoryIds($categoryIds)
    {
        return CourseCategoryModel::query()
            ->inWhere('category_id', $categoryIds)
            ->execute();
    }

    /**
     * @param array $courseIds
     * @return ResultsetInterface|Resultset|CourseCategoryModel[]
     */
    public function findByCourseIds($courseIds)
    {
        return CourseCategoryModel::query()
            ->inWhere('course_id', $courseIds)
            ->execute();
    }

}
