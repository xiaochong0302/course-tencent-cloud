<?php

namespace App\Services\Frontend\Reward;

use App\Repos\Reward as RewardRepo;
use App\Services\Frontend\Service as FrontendService;

class OptionList extends FrontendService
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