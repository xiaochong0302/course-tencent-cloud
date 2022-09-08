<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

use App\Library\Benchmark;
use App\Models\User as UserModel;
use App\Services\Logic\Notice\External\DingTalk\ServerMonitor as ServerMonitorNotice;
use App\Services\Search\UserSearcher;
use GatewayClient\Gateway;

class ServerMonitorTask extends Task
{

    public function mainAction()
    {
        $robot = $this->getSettings('dingtalk.robot');

        if ($robot['enabled'] == 0) return;

        $items = [
            'cpu' => $this->checkCpu(),
            'memory' => $this->checkMemory(),
            'disk' => $this->checkDisk(),
            'mysql' => $this->checkMysql(),
            'redis' => $this->checkRedis(),
            'xunsearch' => $this->checkXunsearch(),
            'websocket' => $this->checkWebsocket(),
        ];

        foreach ($items as $key => $value) {
            if (empty($value)) {
                unset($items[$key]);
            }
        }

        if (empty($items)) return;

        $content = implode("\n", $items);

        $notice = new ServerMonitorNotice();

        $notice->createTask($content);
    }

    protected function checkCpu()
    {
        $cpuCount = $this->getCpuCount();

        $load = sys_getloadavg();

        if ($load[1] > $cpuCount * 0.8) {
            return sprintf("cpu负载超过%s", $load[1]);
        }
    }

    protected function checkMemory()
    {
        $memInfo = file_get_contents('/proc/meminfo');

        $total = null;

        if (preg_match('/MemTotal\:\s+(\d+) kB/', $memInfo, $totalMatches)) {
            $total = $totalMatches[1];
        }

        if ($total === null) return;

        $available = null;

        if (preg_match('/MemAvailable\:\s+(\d+) kB/', $memInfo, $avaMatches)) {
            $available = $avaMatches[1];
        }

        if ($available === null) return;

        $left = 100 * ($available / $total);

        if ($left < 20) {
            return sprintf("memory剩余不足%s%%", round($left));
        }
    }

    protected function checkDisk()
    {
        $free = disk_free_space('/');
        $total = disk_total_space('/');

        $left = 100 * $free / $total;

        if ($left < 20) {
            return sprintf("disk剩余不足%s%%", round($left));
        }
    }

    protected function checkMysql()
    {
        try {

            $benchmark = new Benchmark();

            $benchmark->start();

            $user = UserModel::findFirst();

            $benchmark->stop();

            $elapsedTime = $benchmark->getElapsedTime();

            if ($user === false) {
                return sprintf("mysql查询失败");
            }

            if ($elapsedTime > 1) {
                return sprintf("mysql查询响应超过%s秒", round($elapsedTime, 2));
            }

        } catch (\Exception $e) {
            return sprintf("mysql可能存在异常");
        }
    }

    protected function checkRedis()
    {
        try {

            $benchmark = new Benchmark();

            $benchmark->start();

            $site = $this->getSettings('site');

            $benchmark->stop();

            $elapsedTime = $benchmark->getElapsedTime();

            if (empty($site)) {
                return sprintf("redis查询失败");
            }

            if ($elapsedTime > 1) {
                return sprintf("redis查询响应超过%s秒", round($elapsedTime, 2));
            }

        } catch (\Exception $e) {
            return sprintf("redis可能存在异常");
        }
    }

    protected function checkXunsearch()
    {
        try {

            $benchmark = new Benchmark();

            $benchmark->start();

            $searcher = new UserSearcher();

            $user = $searcher->search('id:10000');

            $benchmark->stop();

            $elapsedTime = $benchmark->getElapsedTime();

            if (empty($user)) {
                return sprintf("xunsearch搜索失败");
            }

            if ($elapsedTime > 1) {
                return sprintf("xunsearch搜索响应超过%s秒", round($elapsedTime, 2));
            }

        } catch (\Exception $e) {
            return sprintf("xunsearch可能存在异常");
        }
    }

    protected function checkWebsocket()
    {
        try {

            $benchmark = new Benchmark();

            $config = $this->getConfig();

            Gateway::$registerAddress = $config->path('websocket.register_address');

            $benchmark->start();

            Gateway::isUidOnline(10000);

            $benchmark->stop();

            $elapsedTime = $benchmark->getElapsedTime();

            if ($elapsedTime > 1) {
                return sprintf("websocket响应超过%s秒", round($elapsedTime, 2));
            }

        } catch (\Exception $e) {
            return sprintf("websocket可能存在异常");
        }
    }

    protected function getCpuCount()
    {
        $cpuInfo = file_get_contents('/proc/cpuinfo');

        preg_match("/^cpu cores\s:\s(\d+)/m", $cpuInfo, $matches);

        $coreCount = intval($matches[1]);

        preg_match_all("/^processor/m", $cpuInfo, $matches);

        $processorCount = count($matches[0]);

        return $coreCount * $processorCount;
    }

}