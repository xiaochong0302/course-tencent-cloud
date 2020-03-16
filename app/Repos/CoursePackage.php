<?php

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
        $result = CoursePackageModel::findFirst([
            'conditions' => 'course_id = :course_id: AND package_id = :package_id:',
            'bind' => ['course_id' => $courseId, 'package_id' => $packageId],
        ]);

        return $result;
    }

    /**
     * @param int $courseId
     * @return ResultsetInterface|Resultset|CoursePackageModel[]
     */
    public function findByCourseId($courseId)
    {
        $result = CoursePackageModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->execute();

        return $result;
    }

    /**
     * @param int $packageId
     * @return ResultsetInterface|Resultset|CoursePackageModel[]
     */
    public function findByPackageId($packageId)
    {
        $result = CoursePackageModel::query()
            ->where('package_id = :package_id:', ['package_id' => $packageId])
            ->execute();

        return $result;
    }

}
