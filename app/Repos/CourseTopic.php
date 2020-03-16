<?php

namespace App\Repos;

use App\Models\CourseTopic as CourseTopicModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CourseTopic extends Repository
{

    /**
     * @param int $courseId
     * @param int $topicId
     * @return CourseTopicModel|Model|bool
     */
    public function findCourseTopic($courseId, $topicId)
    {
        $result = CourseTopicModel::findFirst([
            'conditions' => 'course_id = :course_id: AND topic_id = :topic_id:',
            'bind' => ['course_id' => $courseId, 'topic_id' => $topicId],
        ]);

        return $result;
    }

    /**
     * @param array $topicIds
     * @return ResultsetInterface|Resultset|CourseTopicModel[]
     */
    public function findByTopicIds($topicIds)
    {
        $result = CourseTopicModel::query()
            ->inWhere('topic_id', $topicIds)
            ->execute();

        return $result;
    }

    /**
     * @param array $courseIds
     * @return ResultsetInterface|Resultset|CourseTopicModel[]
     */
    public function findByCourseIds($courseIds)
    {
        $result = CourseTopicModel::query()
            ->inWhere('course_id', $courseIds)
            ->execute();

        return $result;
    }

}
