<?php

namespace App\Repos;

use App\Models\CoursePackage as CoursePackageModel;

class CoursePackage extends Repository
{

    public function findCoursePackage($courseId, $packageId)
    {
        $result = CoursePackageModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('package_id = :package_id:', ['package_id' => $packageId])
            ->orderBy('id DESC')
            ->execute()
            ->getFirst();

        return $result;
    }

    public function findByCategoryIds($packageIds)
    {
        $result = CoursePackageModel::query()
            ->inWhere('package_id', $packageIds)
            ->execute();

        return $result;
    }

    public function findByCourseIds($courseIds)
    {
        $result = CoursePackageModel::query()
            ->inWhere('course_id', $courseIds)
            ->execute();

        return $result;
    }

}
