<?php

namespace App\Caches;

use App\Models\Order as OrderModel;
use App\Models\User as UserModel;

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
        return [
            'user_count' => $this->countUsers(),
            'order_count' => $this->countOrders(),
            'sale_amount' => $this->sumSales(),
        ];
    }

    protected function countUsers()
    {
        return (int)UserModel::count([
            'conditions' => 'create_time > :time:',
            'bind' => ['time' => strtotime('today')],
        ]);
    }

    protected function countOrders()
    {
        return (int)OrderModel::count([
            'conditions' => 'create_time > :time: AND status = :status:',
            'bind' => [
                'time' => strtotime('today'),
                'status' => OrderModel::STATUS_FINISHED,
            ],
        ]);
    }

    protected function sumSales()
    {
        return (float)OrderModel::sum([
            'column' => 'amount',
            'conditions' => 'create_time > :time: AND status = :status:',
            'bind' => [
                'time' => strtotime('today'),
                'status' => OrderModel::STATUS_FINISHED,
            ],
        ]);
    }

}
