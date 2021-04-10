<?php

namespace App\Builders;

use App\Repos\User as UserRepo;

class CommentList extends Builder
{

    public function handleUsers(array $comments)
    {
        $users = $this->getUsers($comments);

        foreach ($comments as $key => $comment) {
            $comments[$key]['owner'] = $users[$comment['owner_id']] ?? new \stdClass();
            $comments[$key]['to_user'] = $users[$comment['to_user_id']] ?? new \stdClass();
        }

        return $comments;
    }

    public function getUsers(array $comments)
    {
        $ownerIds = kg_array_column($comments, 'owner_id');
        $toUserIds = kg_array_column($comments, 'to_user_id');
        $ids = array_merge($ownerIds, $toUserIds);

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar']);

        $baseUrl = kg_cos_url();

        $result = [];

        foreach ($users->toArray() as $user) {
            $user['avatar'] = $baseUrl . $user['avatar'];
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
