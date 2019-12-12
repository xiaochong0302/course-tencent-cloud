<?php

namespace App\Repos;

use App\Models\CourseCategory as CourseCategoryModel;

class CourseCategory extends Repository
{

    public function findCourseCategory($courseId, $categoryId)
    {
        $result = CourseCategoryModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('category_id = :category_id:', ['category_id' => $categoryId])
            ->orderBy('id DESC')
            ->execute()
            ->getFirst();

        return $result;
    }

    public function findByCategoryIds($categoryIds)
    {
        $result = CourseCategoryModel::query()
            ->inWhere('category_id', $categoryIds)
            ->execute();

        return $result;
    }

    public function findByCourseIds($courseIds)
    {
        $result = CourseCategoryModel::query()
            ->inWhere('course_id', $courseIds)
            ->execute();

        return $result;
    }

}
