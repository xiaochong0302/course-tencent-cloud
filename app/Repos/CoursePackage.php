<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\CoursePackage as CoursePackageModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CoursePackage extends Repository
{

    /**
     * @param int $courseId
     * @param int $packageId
     * @return CoursePackageModel|Model|bool
     */
    public function findCoursePackage($courseId, $packageId)
    {
        return CoursePackageModel::findFirst([
            'conditions' => 'course_id = :course_id: AND package_id = :package_id:',
            'bind' => ['course_id' => $courseId, 'package_id' => $packageId],
        ]);
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|CoursePackageModel[]
     */
    public function findByCourseId($courseId)
    {
        return CoursePackageModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->execute();
    }

    /**
     * @param int $packageId
     * @return ResultsetInterface|Resultset|CoursePackageModel[]
     */
    public function findByPackageId($packageId)
    {
        return CoursePackageModel::query()
            ->where('package_id = :package_id:', ['package_id' => $packageId])
            ->execute();
    }

}
