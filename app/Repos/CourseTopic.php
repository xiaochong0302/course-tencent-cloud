<?php

namespace App\Repos;

use App\Models\CourseTopic as CourseTopicModel;

class CourseTopic extends Repository
{

    public function findCourseTopic($courseId, $topicId)
    {
        $result = CourseTopicModel::query()
            ->where('course_id = :course_id:', ['course_id' => $courseId])
            ->andWhere('topic_id = :topic_id:', ['topic_id' => $topicId])
            ->execute()
            ->getFirst();

        return $result;
    }

    public function findByTopicIds($topicIds)
    {
        $result = CourseTopicModel::query()
            ->inWhere('topic_id', $topicIds)
            ->execute();

        return $result;
    }

    public function findByCourseIds($courseIds)
    {
        $result = CourseTopicModel::query()
            ->inWhere('course_id', $courseIds)
            ->execute();

        return $result;
    }

}
