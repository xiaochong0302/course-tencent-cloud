<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Comment;

use App\Builders\CommentList as CommentListBuilder;
use App\Repos\CommentLike as CommentLikeRepo;

trait ListTrait
{

    public function handleComments($pager)
    {
        if ($pager->total_items == 0) {
            return $pager;
        }

        $comments = $pager->items->toArray();

        $builder = new CommentListBuilder();

        $users = $builder->getUsers($comments);

        $meMappings = $this->getMeMappings($comments);

        $items = [];

        foreach ($comments as $comment) {

            $owner = $users[$comment['owner_id']] ?? new \stdClass();
            $toUser = $users[$comment['to_user_id']] ?? new \stdClass();
            $me = $meMappings[$comment['id']];

            $items[] = [
                'id' => $comment['id'],
                'content' => $comment['content'],
                'parent_id' => $comment['parent_id'],
                'like_count' => $comment['like_count'],
                'reply_count' => $comment['reply_count'],
                'create_time' => $comment['create_time'],
                'update_time' => $comment['update_time'],
                'to_user' => $toUser,
                'owner' => $owner,
                'me' => $me,
            ];
        }

        $pager->items = $items;

        return $pager;
    }

    protected function getMeMappings($comments)
    {
        $user = $this->getCurrentUser(true);

        $likeRepo = new CommentLikeRepo();

        $likedIds = [];

        if ($user->id > 0) {
            $likes = $likeRepo->findByUserId($user->id)
                ->filter(function ($like) {
                    if ($like->deleted == 0) {
                        return $like;
                    }
                });
            $likedIds = array_column($likes, 'comment_id');
        }

        $result = [];

        foreach ($comments as $comment) {
            $result[$comment['id']] = [
                'logged' => $user->id > 0 ? 1 : 0,
                'liked' => in_array($comment['id'], $likedIds) ? 1 : 0,
                'owned' => $comment['owner_id'] == $user->id ? 1 : 0,
            ];
        }

        return $result;
    }

}
