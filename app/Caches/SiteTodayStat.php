<?php

namespace App\Caches;

use App\Repos\Stat as StatRepo;

class SiteTodayStat extends Cache
{

    protected $lifetime = 1 * 3600;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'site_today_stat';
    }

    public function getContent($id = null)
    {
        $statRepo = new StatRepo();

        $date = date('Y-m-d');

        $saleCount = $statRepo->countDailySales($date);
        $saleAmount = $statRepo->sumDailySales($date);
        $refundAmount = $statRepo->sumDailyRefunds($date);
        $registerCount = $statRepo->countDailyRegisteredUser($date);

        return [
            'sale_count' => $saleCount,
            'sale_amount' => $saleAmount,
            'refund_amount' => $refundAmount,
            'register_count' => $registerCount,
        ];
    }

}
