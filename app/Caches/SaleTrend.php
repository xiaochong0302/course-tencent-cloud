<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Caches;

use App\Models\Order as OrderModel;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\ResultsetInterface;

class SaleTrend extends Cache
{

    protected $lifetime = 2 * 3600;

    public function getLifetime()
    {
        return $this->lifetime;
    }

    public function getKey($id = null)
    {
        return 'sale_trend';
    }

    public function getContent($id = null)
    {

    }

    /**
     * @param OrderModel[] $sales
     * @param int $days
     * @return array
     */
    protected function handleSales($sales, $days = 7)
    {
        $result = [];

        foreach (array_reverse(range(1, $days)) as $num) {
            $date = date('Y-m-d', strtotime("-{$num} days"));
            $result[$date] = 0;
        }

        foreach ($sales as $sale) {
            $date = date('Y-m-d', $sale->create_time);
            $result[$date] += $sale->amount;
        }

        return $result;
    }

    /**
     * @param int $days
     * @return ResultsetInterface|Resultset|OrderModel[]
     */
    protected function findSales($days = 7)
    {
        $time = strtotime("-{$days} days");

        return OrderModel::query()
            ->where('status = :status:', ['status' => OrderModel::STATUS_FINISHED])
            ->andWhere('create_time > :time:', ['time' => $time])
            ->execute();
    }

}
