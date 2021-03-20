<?php

namespace App\Services\Logic\FlashSale;

use App\Models\FlashSale as FlashSaleModel;
use App\Repos\FlashSale as FlashSaleRepo;
use App\Services\Logic\Service;

class SaleList extends Service
{

    /**
     * @var string cos存储URL
     */
    protected $cosUrl;

    public function handle()
    {
        $this->cosUrl = kg_cos_url();

        $days = 3;

        $date = date('Y-m-d');

        $saleRepo = new FlashSaleRepo();

        $sales = $saleRepo->findFutureSales($date);

        if ($sales->count() == 0) return [];

        return $this->handleSales($sales, $days);
    }

    protected function handleSales($sales, $days)
    {
        $dates = $this->getFutureDates($days);

        $result = [];

        foreach ($dates as $date) {
            $result[] = [
                'date' => date('m / d', strtotime($date)),
                'items' => $this->getDateSales($sales, $date),
            ];
        }

        return $result;
    }

    /**
     * @param FlashSaleModel[] $sales
     * @param string $date
     * @return array
     */
    protected function getDateSales($sales, $date)
    {
        $result = [];

        $schedules = FlashSaleModel::schedules();

        $hasActiveStatus = false;

        foreach ($schedules as $schedule) {

            $items = [];

            $hour = $schedule['hour'];

            foreach ($sales as $sale) {

                $sale->item_info = $this->handleItemInfo($sale->item_type, $sale->item_info);

                $item = [
                    'id' => $sale->id,
                    'stock' => $sale->stock,
                    'price' => $sale->price,
                    'item_id' => $sale->item_id,
                    'item_type' => $sale->item_type,
                    'item_info' => $sale->item_info,
                ];

                $case1 = $sale->start_time <= strtotime($date);
                $case2 = $sale->end_time > strtotime($date);
                $case3 = in_array($hour, $sale->schedules);

                if ($case1 && $case2 && $case3) {
                    $items[] = $item;
                }
            }

            $status = $this->getSaleStatus($date, $hour);

            if ($status == 'active') {
                $hasActiveStatus = true;
            }

            $result[] = [
                'hour' => sprintf('%02d:00', $hour),
                'selected' => $status == 'active' ? 1 : 0,
                'status' => $status,
                'items' => $items,
            ];
        }

        /**
         * 所在date无active状态，设置第一项为selected
         */
        if (!$hasActiveStatus) {
            $result[0]['selected'] = 1;
        }

        return $result;
    }

    protected function getFutureDates($days = 7)
    {
        $result = [];

        for ($i = 0; $i < $days; $i++) {
            $result[] = date('Y-m-d', strtotime("+{$i} days"));
        }

        return $result;
    }

    protected function getSaleStatus($date, $hour)
    {
        if (strtotime($date) - strtotime('today') > 0) {
            return 'pending';
        }

        $curHour = date('H');

        if ($curHour >= $hour + 2) {
            return 'finished';
        } elseif ($curHour >= $hour && $curHour < $hour + 2) {
            return 'active';
        } else {
            return 'pending';
        }
    }

    protected function handleItemInfo($itemType, &$itemInfo)
    {
        if ($itemType == FlashSaleModel::ITEM_COURSE) {
            $itemInfo['course']['cover'] = $this->cosUrl . $itemInfo['course']['cover'];
        } elseif ($itemType == FlashSaleModel::ITEM_PACKAGE) {
            $itemInfo['package']['cover'] = $this->cosUrl . $itemInfo['package']['cover'];
        } elseif ($itemType == FlashSaleModel::ITEM_VIP) {
            $itemInfo['vip']['cover'] = $this->cosUrl . $itemInfo['vip']['cover'];
        }

        return $itemInfo;
    }

}
