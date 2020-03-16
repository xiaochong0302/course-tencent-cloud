<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Comment as CommentModel;
use App\Models\CommentVote as CommentVoteModel;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class Comment extends Repository
{

    /**
     * @param array $where
     * @param string $sort
     * @param int $page
     * @param int $limit
     * @return \stdClass
     */
    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(CommentModel::class);

        $builder->where('1 = 1');

        if (!empty($where['id'])) {
            $builder->andWhere('id = :id:', ['id' => $where['id']]);
        }

        if (!empty($where['parent_id'])) {
            $builder->andWhere('parent_id = :parent_id:', ['parent_id' => $where['parent_id']]);
        }

        if (!empty($where['course_id'])) {
            $builder->andWhere('course_id = :course_id:', ['course_id' => $where['course_id']]);
        }

        if (!empty($where['chapter_id'])) {
            $builder->andWhere('chapter_id = :chapter_id:', ['chapter_id' => $where['chapter_id']]);
        }

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
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
     * @return CommentModel|Model|bool
     */
    public function findById($id)
    {
        $result = CommentModel::findFirst($id);

        return $result;
    }

    /**
     * @param array $ids
     * @param string|array $columns
     * @return ResultsetInterface|Resultset|CommentModel[]
     */
    public function findByIds($ids, $columns = '*')
    {
        $result = CommentModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    public function countReplies($commentId)
    {
        $count = CommentModel::count([
            'conditions' => 'parent_id = :parent_id: AND deleted = 0',
            'bind' => ['parent_id' => $commentId],
        ]);

        return $count;
    }

    public function countAgrees($commentId)
    {
        $type = CommentVoteModel::TYPE_AGREE;

        $count = CommentVoteModel::count([
            'conditions' => 'comment_id = :comment_id: AND type = :type: AND deleted = 0',
            'bind' => ['comment_id' => $commentId, 'type' => $type],
        ]);

        return $count;
    }

    public function countOpposes($commentId)
    {
        $type = CommentVoteModel::TYPE_OPPOSE;

        $count = CommentVoteModel::count([
            'conditions' => 'comment_id = :comment_id: AND type = :type: AND deleted = 0',
            'bind' => ['comment_id' => $commentId, 'type' => $type],
        ]);

        return $count;
    }

}
