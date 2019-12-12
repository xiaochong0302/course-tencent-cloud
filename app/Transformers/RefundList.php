<?php

namespace App\Transformers;

use App\Repos\User as UserRepo;

class RefundList extends Transformer
{

    public function handleUsers($refunds)
    {
        $users = $this->getUsers($refunds);

        foreach ($refunds as $key => $refund) {
            $refunds[$key]['user'] = $users[$refund['user_id']];
        }

        return $refunds;
    }

    protected function getUsers($refunds)
    {
        $ids = kg_array_column($refunds, 'user_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar'])->toArray();

        $result = [];

        foreach ($users as $user) {
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
