<?php

namespace App\Builders;

use App\Repos\User as UserRepo;

class RefundList extends Builder
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

        $users = $userRepo->findByIds($ids, ['id', 'name', 'avatar']);

        $result = [];

        $imgBaseUrl = kg_img_base_url();

        foreach ($users->toArray() as $user) {

            $user['avatar'] = $imgBaseUrl . $user['avatar'];

            $result[$user['id']] = $user;
        }

        return $result;
    }

}
