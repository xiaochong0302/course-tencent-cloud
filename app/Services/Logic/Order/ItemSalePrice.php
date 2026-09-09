<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Services\Logic\Order;

use App\Models\KgProduct as KgProductModel;
use App\Repos\Course as CourseRepo;
use App\Repos\Vip as VipRepo;

class ItemSalePrice
{

    public function handle(int $itemId, int $itemType): array
    {
        $result = [
            'market_price' => 0,
            'vip_price' => 0,
        ];

        if ($itemType == KgProductModel::ITEM_COURSE) {

            $courseRepo = new CourseRepo();

            $course = $courseRepo->findById($itemId);

            $result['market_price'] = $course->market_price;
            $result['vip_price'] = $course->vip_price;

        } elseif ($itemType == KgProductModel::ITEM_VIP) {

            $vipRepo = new VipRepo();

            $vip = $vipRepo->findById($itemId);

            $result['market_price'] = $vip->price;
            $result['vip_price'] = $vip->price;

        }

        return $result;
    }

}
