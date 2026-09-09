<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Repos\Stat as StatRepo;

class SiteTodayStat extends Cache
{

    /**
     * @var int
     */
    protected int $lifetime = 1800;

    public function getKey($id = null): string
    {
        return 'site-today-stat';
    }

    public function getContent($id = null): array
    {
        $statRepo = new StatRepo();

        $date = date('Y-m-d');

        $saleCount = $statRepo->countDailySales($date);
        $saleAmount = $statRepo->sumDailySales($date);
        $registerCount = $statRepo->countDailyRegisteredUsers($date);

        return [
            'sale_count' => $saleCount,
            'sale_amount' => $saleAmount,
            'register_count' => $registerCount,
        ];
    }

}
