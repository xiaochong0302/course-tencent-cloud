<?php

namespace App\Repos;

use App\Models\ReplyLike as ReplyVoteModel;

class ReplyLike extends Repository
{

    public function findReplyLike($replyId, $userId)
    {
        $result = ReplyVoteModel::query()
            ->where('reply_id = :reply_id:', ['reply_id' => $replyId])
            ->andWhere('user_id = :user_id:', ['user_id' => $userId])
            ->execute()->getFirst();

        return $result;
    }

    public function paginate($where = [], $sort = 'latest', $page = 1, $limit = 15)
    {
        $builder = $this->modelsManager->createBuilder();

        $builder->from(ReplyVoteModel::class);

        $builder->where('1 = 1');

        if (!empty($where['reply_id'])) {
            $builder->andWhere('reply_id = :reply_id:', ['reply_id' => $where['reply_id']]);
        }

        if (!empty($where['user_id'])) {
            $builder->andWhere('user_id = :user_id:', ['user_id' => $where['user_id']]);
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

        return $pager;
    }

}
