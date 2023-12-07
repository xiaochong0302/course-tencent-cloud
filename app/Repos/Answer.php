<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Answer as AnswerModel;
use App\Models\AnswerLike as AnswerLikeModel;
use App\Models\Comment as CommentModel;
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

        if (!empty($where['published'])) {
            if (is_array($where['published'])) {
                $builder->inWhere('published', $where['published']);
            } else {
                $builder->andWhere('published = :published:', ['published' => $where['published']]);
            }
        }

        if (!empty($where['create_time'][0]) && !empty($where['create_time'][1])) {
            $startTime = strtotime($where['create_time'][0]);
            $endTime = strtotime($where['create_time'][1]);
            $builder->betweenWhere('create_time', $startTime, $endTime);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('deleted = :deleted:', ['deleted' => $where['deleted']]);
        }

        if ($sort == 'reported') {
            $builder->andWhere('report_count > 0');
        }

        switch ($sort) {
            case 'popular':
                $orderBy = 'like_count DESC, id DESC';
                break;
            case 'accepted':
                $orderBy = 'accepted DESC, id DESC';
                break;
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

    public function countComments($answerId)
    {
        return (int)CommentModel::count([
            'conditions' => 'item_id = ?1 AND item_type = ?2 AND published = ?3 AND deleted = 0',
            'bind' => [1 => $answerId, 2 => CommentModel::ITEM_ANSWER, 3 => CommentModel::PUBLISH_APPROVED],
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
