<?php

namespace App\Services\Frontend\Vip;

use App\Repos\Vip as VipRepo;
use App\Services\Frontend\Service as FrontendService;

class OptionList extends FrontendService
{

    public function handle()
    {
        $vipRepo = new VipRepo();

        $vips = $vipRepo->findAll(['deleted' => 0]);

        if ($vips->count() == 0) {
            return [];
        }

        $result = [];

        foreach ($vips as $vip) {
            $result[] = [
                'id' => $vip->id,
                'title' => $vip->title,
                'expiry' => $vip->expiry,
                'price' => $vip->price,
            ];
        }

        return $result;
    }

}