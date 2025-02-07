<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

use App\Models\Order as OrderModel;
use App\Repos\Stat as StatRepo;

class Stat extends Service
{

    public function hotSales()
    {
        $type = $this->request->getQuery('type', 'int', OrderModel::ITEM_COURSE);
        $year = $this->request->getQuery('year', 'int', date('Y'));
        $month = $this->request->getQuery('month', 'int', date('m'));

        $prev = $this->getPrevMonth($year, $month);

        return [
            [
                'title' => sprintf('%02d-%02d', $year, $month),
                'sales' => $this->handleHotSales($type, $year, $month),
            ],
            [
                'title' => sprintf('%02d-%02d', $prev['year'], $prev['month']),
                'sales' => $this->handleHotSales($type, $prev['year'], $prev['month']),
            ],
        ];
    }

    public function sales()
    {
        $year = $this->request->getQuery('year', 'int', date('Y'));
        $month = $this->request->getQuery('month', 'int', date('m'));

        $prev = $this->getPrevMonth($year, $month);
        $currSales = $this->handleSales($year, $month);
        $prevSales = $this->handleSales($prev['year'], $prev['month']);

        $currMonth = sprintf('%02d-%02d', $year, $month);
        $prevMonth = sprintf('%02d-%02d', $prev['year'], $prev['month']);

        $items = [];

        foreach (range(1, 31) as $day) {
            $date = sprintf('%02d', $day);
            $items[] = [
                'date' => $date,
                $currMonth => $currSales[$date] ?? 0,
                $prevMonth => $prevSales[$date] ?? 0,
            ];
        }

        return $items;
    }

    public function refunds()
    {
        $year = $this->request->getQuery('year', 'int', date('Y'));
        $month = $this->request->getQuery('month', 'int', date('m'));

        $prev = $this->getPrevMonth($year, $month);
        $currRefunds = $this->handleRefunds($year, $month);
        $prevRefunds = $this->handleRefunds($prev['year'], $prev['month']);

        $currMonth = sprintf('%02d-%02d', $year, $month);
        $prevMonth = sprintf('%02d-%02d', $prev['year'], $prev['month']);

        $items = [];

        foreach (range(1, 31) as $day) {
            $date = sprintf('%02d', $day);
            $items[] = [
                'date' => $date,
                $currMonth => $currRefunds[$date] ?? 0,
                $prevMonth => $prevRefunds[$date] ?? 0,
            ];
        }

        return $items;
    }

    public function registeredUsers()
    {
        $year = $this->request->getQuery('year', 'int', date('Y'));
        $month = $this->request->getQuery('month', 'int', date('m'));

        $prev = $this->getPrevMonth($year, $month);
        $currUsers = $this->handleRegisteredUsers($year, $month);
        $prevUsers = $this->handleRegisteredUsers($prev['year'], $prev['month']);

        $currMonth = sprintf('%02d-%02d', $year, $month);
        $prevMonth = sprintf('%02d-%02d', $prev['year'], $prev['month']);

        $items = [];

        foreach (range(1, 31) as $day) {
            $date = sprintf('%02d', $day);
            $items[] = [
                'date' => $date,
                $currMonth => $currUsers[$date] ?? 0,
                $prevMonth => $prevUsers[$date] ?? 0,
            ];
        }

        return $items;
    }

    public function onlineUsers()
    {
        $year = $this->request->getQuery('year', 'int', date('Y'));
        $month = $this->request->getQuery('month', 'int', date('m'));

        $prev = $this->getPrevMonth($year, $month);
        $currUsers = $this->handleOnlineUsers($year, $month);
        $prevUsers = $this->handleOnlineUsers($prev['year'], $prev['month']);

        $currMonth = sprintf('%02d-%02d', $year, $month);
        $prevMonth = sprintf('%02d-%02d', $prev['year'], $prev['month']);

        $items = [];

        foreach (range(1, 31) as $day) {
            $date = sprintf('%02d', $day);
            $items[] = [
                'date' => $date,
                $currMonth => $currUsers[$date] ?? 0,
                $prevMonth => $prevUsers[$date] ?? 0,
            ];
        }

        return $items;
    }

    public function getYearOptions()
    {
        $end = date('Y');

        $start = $end - 3;

        return range($start, $end);
    }

    public function getMonthOptions()
    {
        $options = [];

        foreach (range(1, 12) as $value) {
            $options[] = sprintf('%02d', $value);
        }
        return $options;
    }

    protected function isCurrMonth($year, $month)
    {
        $yearOk = date('Y') == $year;
        $monthOk = date('m') == $month;

        return $yearOk && $monthOk;
    }

    protected function getLifetime()
    {
        return strtotime('tomorrow') - time();
    }

    protected function getPrevMonth($year, $month)
    {
        $currentMonthTime = strtotime("{$year}-{$month}");

        $prevMonthTime = strtotime('-1 month', $currentMonthTime);

        return [
            'year' => date('Y', $prevMonthTime),
            'month' => date('m', $prevMonthTime),
        ];
    }

    protected function getMonthDates($year, $month)
    {
        $startTime = strtotime("{$year}-{$month}-01");

        $days = date('t', $startTime);

        $result = [];

        foreach (range(1, $days) as $day) {
            $result[] = sprintf('%04d-%02d-%02d', $year, $month, $day);
        }

        return $result;
    }

    protected function handleHotSales($type, $year, $month)
    {
        $keyName = "stat_hot_sales:{$type}_{$year}_{$month}";

        $cache = $this->getCache();

        $items = $cache->get($keyName);

        if (!$items) {

            $statRepo = new StatRepo();

            $orders = $statRepo->findMonthlyOrders($type, $year, $month);

            $items = [];

            if ($orders->count() > 0) {

                foreach ($orders as $order) {
                    $key = $order->item_id;
                    if (!isset($items[$key])) {
                        $items[$key] = [
                            'title' => $order->subject,
                            'total_count' => 1,
                            'total_amount' => $order->amount,
                        ];
                    } else {
                        $items[$key]['total_count'] += 1;
                        $items[$key]['total_amount'] += $order->amount;
                    }
                }

                $totalCount = array_column($items, 'total_count');

                array_multisort($totalCount, SORT_DESC, $items);
            }

            $queryMonth = "{$year}-{$month}";

            $currMonth = date('Y-m');

            if ($queryMonth < $currMonth) {
                $cache->save($keyName, $items, 86400);
            } else {
                $cache->save($keyName, $items, 3600);
            }
        }

        return $items;
    }

    protected function handleSales($year, $month)
    {
        $keyName = "stat_sales:{$year}_{$month}";

        $redis = $this->getRedis();

        $list = $redis->hGetAll($keyName);

        $statRepo = new StatRepo();

        $currDate = date('Y-m-d');
        $currDay = date('d');

        if (!$list) {
            $dates = $this->getMonthDates($year, $month);
            foreach ($dates as $date) {
                $key = substr($date, -2);
                if ($date < $currDate) {
                    $list[$key] = $statRepo->sumDailySales($date);
                } else {
                    $list[$key] = 0;
                }
            }
            $redis->hMSet($keyName, $list);
            $redis->expire($keyName, $this->getLifetime());
        }

        if ($this->isCurrMonth($year, $month)) {
            $list[$currDay] = $statRepo->sumDailySales($currDate);
        }

        return $list;
    }

    protected function handleRefunds($year, $month)
    {
        $keyName = "stat_refunds:{$year}_{$month}";

        $redis = $this->getRedis();

        $list = $redis->hGetAll($keyName);

        $statRepo = new StatRepo();

        $currDate = date('Y-m-d');
        $currDay = date('d');

        if (!$list) {
            $dates = $this->getMonthDates($year, $month);
            foreach ($dates as $date) {
                $key = substr($date, -2);
                if ($date < $currDate) {
                    $list[$key] = $statRepo->sumDailyRefunds($date);
                } else {
                    $list[$key] = 0;
                }
            }
            $redis->hMSet($keyName, $list);
            $redis->expire($keyName, $this->getLifetime());
        }

        if ($this->isCurrMonth($year, $month)) {
            $list[$currDay] = $statRepo->sumDailyRefunds($currDate);
        }

        return $list;
    }

    protected function handleRegisteredUsers($year, $month)
    {
        $keyName = "stat_reg_users:{$year}_{$month}";

        $redis = $this->getRedis();

        $list = $redis->hGetAll($keyName);

        $statRepo = new StatRepo();

        $currDate = date('Y-m-d');
        $currDay = date('d');

        if (!$list) {
            $dates = $this->getMonthDates($year, $month);
            foreach ($dates as $date) {
                $key = substr($date, -2);
                if ($date < $currDate) {
                    $list[$key] = $statRepo->countDailyRegisteredUsers($date);
                } else {
                    $list[$key] = 0;
                }
            }
            $redis->hMSet($keyName, $list);
            $redis->expire($keyName, $this->getLifetime());
        }

        if ($this->isCurrMonth($year, $month)) {
            $list[$currDay] = $statRepo->countDailyRegisteredUsers($currDate);
        }

        return $list;
    }

    protected function handleOnlineUsers($year, $month)
    {
        $keyName = "stat_online_users:{$year}_{$month}";

        $redis = $this->getRedis();

        $list = $redis->hGetAll($keyName);

        $statRepo = new StatRepo();

        $currDate = date('Y-m-d');
        $currDay = date('d');

        if (!$list) {
            $dates = $this->getMonthDates($year, $month);
            foreach ($dates as $date) {
                $key = substr($date, -2);
                if ($date < $currDate) {
                    $list[$key] = $statRepo->countDailyOnlineUsers($date);
                } else {
                    $list[$key] = 0;
                }
            }
            $redis->hMSet($keyName, $list);
            $redis->expire($keyName, $this->getLifetime());
        }

        if ($this->isCurrMonth($year, $month)) {
            $list[$currDay] = $statRepo->countDailyOnlineUsers($currDate);
        }

        return $list;
    }

}
