<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Repos;

use App\Models\KgPayment as KgPaymentModel;
use App\Models\Order as OrderModel;
use App\Models\OrderStatus as OrderStatusModel;
use App\Models\User as UserModel;

class Stat extends Repository
{

    /**
     * @param string $date
     * @return int
     */
    public function countDailyRegisteredUsers(string $date): int
    {
        list($startTime, $endTime) = $this->getDailyRange($date);

        return (int)UserModel::count([
            'conditions' => 'create_time BETWEEN :start_time: AND :end_time:',
            'bind' => ['start_time' => $startTime, 'end_time' => $endTime],
        ]);
    }


    /**
     * @param string $date
     * @return int
     */
    public function countDailySales(string $date): int
    {
        $sql = [];
        $sql[] = "SELECT count(*) AS total_count FROM {order_status_table} AS os JOIN {order_table} AS o ON os.order_id = o.id";
        $sql[] = "WHERE o.channel IN ({channels}) AND os.status = {status} AND o.create_time BETWEEN {start_time} AND {end_time}";

        $channels = implode(',', $this->getPaymentChannels());

        list($startTime, $endTime) = $this->getDailyRange($date);

        $phql = kg_ph_replace($this->implodeQuerySQL($sql), [
            'order_status_table' => OrderStatusModel::class,
            'order_table' => OrderModel::class,
            'channels' => $channels,
            'status' => OrderModel::STATUS_FINISHED,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        $result = $this->modelsManager->executeQuery($phql);

        return $result[0]['total_count'] ?? 0;
    }

    /**
     * @param string $date
     * @return float
     */
    public function sumDailySales(string $date): float
    {
        $sql = [];
        $sql[] = "SELECT sum(o.amount) AS total_amount FROM {order_status_table} AS os JOIN {order_table} AS o ON os.order_id = o.id";
        $sql[] = "WHERE o.channel IN ({channels}) AND os.status = {status} AND o.create_time BETWEEN {start_time} AND {end_time}";

        $channels = implode(',', $this->getPaymentChannels());

        list($startTime, $endTime) = $this->getDailyRange($date);

        $phql = kg_ph_replace($this->implodeQuerySQL($sql), [
            'order_status_table' => OrderStatusModel::class,
            'order_table' => OrderModel::class,
            'channels' => $channels,
            'status' => OrderModel::STATUS_FINISHED,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        $result = $this->modelsManager->executeQuery($phql);

        return $result[0]['total_amount'] ?? 0.00;
    }

    protected function getPaymentChannels(): array
    {
        return [
            KgPaymentModel::CHANNEL_ALIPAY,
            KgPaymentModel::CHANNEL_WXPAY,
        ];
    }

    protected function getDailyRange(string $date): array
    {
        $startTime = strtotime($date);

        $endTime = $startTime + 86400;

        return [$startTime, $endTime];
    }

    protected function implodeQuerySQL(array $sql): string
    {
        return implode(' ', $sql);
    }

}
