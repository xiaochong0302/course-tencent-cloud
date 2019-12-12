<?php

namespace App\Transformers;

use App\Repos\User as UserRepo;

class TradeList extends Transformer
{

    public function handleUsers($trades)
    {
        $users = $this->getUsers($trades);

        foreach ($trades as $key => $trade) {
            $trades[$key]['user'] = $users[$trade['user_id']];
        }

        return $trades;
    }

    protected function getUsers($trades)
    {
        $ids = kg_array_column($trades, 'user_id');

        $userRepo = new UserRepo();

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar'])->toArray();

        $result = [];

        foreach ($users as $user) {
            $result[$user['id']] = $user;
        }

        return $result;
    }

}
