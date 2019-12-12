<?php

namespace App\Repos;

use App\Models\CourseRelated as CourseRelatedModel;

class CourseRelated extends Repository
{

    public function findCourseRelated($courseId, $relatedId)
    {
        $result = CourseRelatedModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('related_id = :related_id:', ['related_id' => $relatedId])
            ->orderBy('id DESC')
            ->execute()
            ->getFirst();

        return $result;
    }

    public function findByRelatedIds($relatedIds)
    {
        $result = CourseRelatedModel::query()
            ->inWhere('related_id', $relatedIds)
            ->execute();

        return $result;
    }

    public function findByCourseIds($courseIds)
    {
        $result = CourseRelatedModel::query()
            ->inWhere('course_id', $courseIds)
            ->execute();

        return $result;
    }

}
