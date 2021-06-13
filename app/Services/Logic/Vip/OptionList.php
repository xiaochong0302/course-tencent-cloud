<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Vip;

use App\Repos\Vip as VipRepo;
use App\Services\Logic\Service as LogicService;

class OptionList extends LogicService
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