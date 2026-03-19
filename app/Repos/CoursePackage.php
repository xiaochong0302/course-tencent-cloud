<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\CoursePackage as CoursePackageModel;
use Phalcon\Mvc\Model;

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

}
