<?php

namespace App\Repos;

use App\Library\Paginator\Adapter\QueryBuilder as PagerQueryBuilder;
use App\Models\Comment as CommentModel;
use App\Models\CommentLike as CommentLikeModel;


class Comment extends Repository
{

    public function findById($id)
    {
        $result = CommentModel::findFirst($id);

        return $result;
    }

    public function findByIds($ids, $columns = '*')
    {
        $result = CommentModel::query()
            ->columns($columns)
            ->inWhere('id', $ids)
            ->execute();

        return $result;
    }

    public function paginator($where = [], $sort = 'latest', $page = 1, $limit = 15)
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

        if (!empty($where['author_id'])) {
            $builder->andWhere('author_id = :author_id:', ['author_id' => $where['author_id']]);
        }

        if (!empty($where['course_id'])) {
            $builder->andWhere('course_id = :course_id:', ['course_id' => $where['course_id']]);
        }

        if (!empty($where['chapter_id'])) {
            $builder->andWhere('chapter_id = :chapter_id:', ['chapter_id' => $where['chapter_id']]);
        }

        if (isset($where['published'])) {
            $builder->andWhere('c.published = :published:', ['published' => $where['published']]);
        }

        if (isset($where['deleted'])) {
            $builder->andWhere('c.deleted = :deleted:', ['deleted' => $where['deleted']]);
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

    public function countReplies($commentId)
    {
        $count = CommentModel::count([
            'conditions' => 'parent_id = :parent_id: AND deleted = 0',
            'bind' => ['parent_id' => $commentId],
        ]);

        return $count;
    }

    public function countLikes($commentId)
    {
        $count = CommentLikeModel::count([
            'conditions' => 'comment_id = :comment_id: AND deleted = 0',
            'bind' => ['comment_id' => $commentId],
        ]);

        return $count;
    }

}
