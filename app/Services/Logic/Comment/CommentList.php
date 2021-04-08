<?php

namespace App\Services\Logic\Comment;

use App\Builders\CommentList as CommentListBuilder;
use App\Services\Logic\ArticleTrait;
use App\Services\Logic\Service as LogicService;

class CommentList extends LogicService
{

    use ArticleTrait;

    protected function handlePager($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $comments = $pager->items->toArray();

        $builder = new CommentListBuilder();

        $users = $builder->getUsers($comments);

        $items = [];

        foreach ($comments as $comment) {

            $owner = $users[$comment['owner_id']] ?? new \stdClass();
            $toUser = $users[$comment['to_user_id']] ?? new \stdClass();

            $items[] = [
                'id' => $comment['id'],
                'content' => $comment['content'],
                'owner' => $owner,
                'to_user' => $toUser,
                'like_count' => $comment['like_count'],
                'reply_count' => $comment['reply_count'],
                'create_time' => $comment['create_time'],
            ];
        }

        $pager->items = $items;

        return $pager;
    }

}
