<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Course as CourseModel;
use App\Models\CourseTopic as CourseTopicModel;
use App\Models\Topic as TopicModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Topic extends Repository
{

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(TopicModel::class);

        $builder->where('1 = 1');

        if (!empty($where['title'])) {
            $builder->andWhere('title LIKE :title:', ['title' => "%{$where['title']}%"]);
        }

        if (isset($where['published'])) {
            $builder->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        switch ($sort) {
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
     * @param int $id
     * @return TopicModel|Model|bool
     */
    public function findById($id)
    {
        return TopicModel::findFirst($id);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|TopicModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return TopicModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    /**
     * @param int $topicId
     * @return ResultsetInterface|Resultset|CourseModel[]
     */
    public function findCourses($topicId)
    {
        return $this->modelsManager->createBuilder()
            ->columns('c.*')
            ->addFrom(CourseModel::class, 'c')
            ->join(CourseTopicModel::class, 'c.id = ct.course_id', 'ct')
            ->where('ct.topic_id = :topic_id:', ['topic_id' => $topicId])
            ->andWhere('c.deleted = 0')
            ->getQuery()->execute();
    }

    public function countCourses($topicId)
    {
        return CourseTopicModel::count([
            'conditions' => 'topic_id = :topic_id:',
            'bind' => ['topic_id' => $topicId],
        ]);
    }

}
