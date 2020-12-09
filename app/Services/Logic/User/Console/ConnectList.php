<?php

namespace App\Services\Logic\User\Console;

use App\Repos\Connect as ConnectRepo;
use App\Services\Logic\Service;

class ConnectList extends Service
{

    public function handle()
    {
        $user = $this->getLoginUser();

        $params = [
            'user_id' => $user->id,
            'deleted' => 0,
        ];

        $connectRepo = new ConnectRepo();

        $connects = $connectRepo->findAll($params);

        if ($connects->count() == 0) {
            return [];
        }

        $items = [];

        foreach ($connects as $connect) {
            $items[] = [
                'id' => $connect->id,
                'open_id' => $connect->open_id,
                'open_name' => $connect->open_name,
                'open_avatar' => $connect->open_avatar,
                'provider' => $connect->provider,
                'create_time' => $connect->create_time,
                'update_time' => $connect->update_time,
            ];
        }

        return $items;
    }

}
