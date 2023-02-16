<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\CourseTopic as CourseTopicModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class CourseTopic extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();


        $builder->from(CourseTopicModel::class);

        $builder->where('1 = 1');

        if (!empty($where['course_id'])) {
            $builder->andWhere('course_id = :course_id:', ['course_id' => $where['course_id']]);
        }

        if (!empty($where['topic_id'])) {
            $builder->andWhere('topic_id = :topic_id:', ['topic_id' => $where['topic_id']]);
        }

        switch ($sort) {
            case 'oldest':
                $orderBy = 'id ASC';
                break;
            default:
                $orderBy = 'id DESC';
                break;
        }

        $builder->orderBy($orderBy);

        $pager = new PagerQueryBuilder([
            'builder' => $builder,
            'page' => $page,
            'limit' => $limit,
        ]);

        return $pager->paginate();
    }

    /**
     * @param int $courseId
     * @param int $topicId
     * @return CourseTopicModel|Model|bool
     */
    public function findCourseTopic($courseId, $topicId)
    {
        return CourseTopicModel::findFirst([
            'conditions' => 'course_id = :course_id: AND topic_id = :topic_id:',
            'bind' => ['course_id' => $courseId, 'topic_id' => $topicId],
        ]);
    }

    /**
     * @param array $topicIds
     * @return ResultsetInterface|Resultset|CourseTopicModel[]
     */
    public function findByTopicIds($topicIds)
    {
        return CourseTopicModel::query()
            ->inWhere('topic_id', $topicIds)
            ->execute();
    }

    /**
     * @param array $courseIds
     * @return ResultsetInterface|Resultset|CourseTopicModel[]
     */
    public function findByCourseIds($courseIds)
    {
        return CourseTopicModel::query()
            ->inWhere('course_id', $courseIds)
            ->execute();
    }

}
