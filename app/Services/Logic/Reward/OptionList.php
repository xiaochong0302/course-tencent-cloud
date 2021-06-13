<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Reward;

use App\Repos\Reward as RewardRepo;
use App\Services\Logic\Service as LogicService;

class OptionList extends LogicService
{

    public function handle()
    {
        $rewardRepo = new RewardRepo();

        $rewards = $rewardRepo->findAll(['deleted' => 0]);

        if ($rewards->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($rewards as $reward) {
            $result[] = [
                'id' => $reward->id,
                'title' => $reward->title,
                'price' => $reward->price,
            ];
        }

        return $result;
    }

}