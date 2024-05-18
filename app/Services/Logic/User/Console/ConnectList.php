<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\User\Console;

use App\Models\Connect as ConnectModel;
use App\Repos\Connect as ConnectRepo;
use App\Services\Logic\Service as LogicService;

class ConnectList extends LogicService
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

        $excludes = [
            ConnectModel::PROVIDER_WECHAT_OA,
            ConnectModel::PROVIDER_WECHAT_MINI,
        ];

        foreach ($connects as $connect) {
            if (!in_array($connect->provider, $excludes)) {
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
        }

        return $items;
    }

}
