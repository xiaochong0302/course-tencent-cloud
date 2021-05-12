<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Answer as AnswerModel;
use App\Models\AnswerLike as AnswerLikeModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Answer extends Repository
{

    public function paginate($where = [], $sort = 'accepted', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(AnswerModel::class);

        $builder->where('1 = 1');

        if (!empty($where['id'])) {
            $builder->andWhere('id = :id:', ['id' => $where['id']]);
        }

        if (!empty($where['owner_id'])) {
            $builder->andWhere('owner_id = :owner_id:', ['owner_id' => $where['owner_id']]);
        }

        if (!empty($where['question_id'])) {
            $builder->andWhere('question_id = :question_id:', ['question_id' => $where['question_id']]);
        }

        if (isset($where['published'])) {
            $builder->andWhere('published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        switch ($sort) {
            case 'popular':
                $orderBy = 'like_count DESC';
                break;
            case 'latest':
                $orderBy = 'id DESC';
                break;
            default:
                $orderBy = 'accepted DESC, like_count DESC';
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
     * @return AnswerModel|Model|bool
     */
    public function findById($id)
    {
        return AnswerModel::findFirst([
            'conditions' => 'id = :id:',
            'bind' => ['id' => $id],
        ]);
    }

    /**
     * @param array $ids
     * @param array|string $columns
     * @return ResultsetInterface|Resultset|AnswerModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        return AnswerModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();
    }

    public function countAnswers()
    {
        return (int)AnswerModel::count([
            'conditions' => 'published = :published: AND deleted = 0',
            'bind' => ['published' => AnswerModel::PUBLISH_APPROVED],
        ]);
    }

    public function countLikes($answerId)
    {
        return (int)AnswerLikeModel::count([
            'conditions' => 'answer_id = :answer_id: AND deleted = 0',
            'bind' => ['answer_id' => $answerId],
        ]);
    }

}
