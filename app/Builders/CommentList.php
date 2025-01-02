<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Builders;

class CommentList extends Builder
{

    public function handleUsers(array $comments)
    {
        $users = $this->getUsers($comments);

        foreach ($comments as $key => $comment) {
            $comments[$key]['owner'] = $users[$comment['owner_id']] ?? null;
            $comments[$key]['to_user'] = $users[$comment['to_user_id']] ?? null;
        }

        return $comments;
    }

    public function getUsers(array $comments)
    {
        $ownerIds = kg_array_column($comments, 'owner_id');
        $toUserIds = kg_array_column($comments, 'to_user_id');
        $ids = array_merge($ownerIds, $toUserIds);

        return $this->getShallowUserByIds($ids);
    }

}
