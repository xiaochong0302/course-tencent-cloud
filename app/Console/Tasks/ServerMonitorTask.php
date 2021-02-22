<?php

namespace App\Console\Tasks;

use App\Library\Benchmark;
use App\Library\Utils\ServerInfo;
use App\Models\User as UserModel;
use App\Services\DingTalk\Notice\ServerMonitor as ServerMonitorNotice;
use App\Services\Search\UserSearcher;
use GatewayClient\Gateway;

class ServerMonitorTask extends Task
{

    public function mainAction()
    {
        $robot = $this->getSettings('dingtalk.robot');

        if ($robot['enabled'] == 0) return;

        $items = [
            'cpu' => $this->checkCPU(),
            'disk' => $this->checkDisk(),
            'mysql' => $this->checkMysql(),
            'redis' => $this->checkRedis(),
            'xunsearch' => $this->checkXunSearch(),
            'websocket' => $this->checkWebSocket(),
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

    protected function checkCPU()
    {
        $coreCount = $this->getCpuCount();

        $cpu = ServerInfo::cpu();

        if ($cpu[1] > $coreCount * 0.8) {
            return sprintf("cpu负载超过%s", $cpu[1]);
        }
    }

    protected function checkDisk()
    {
        $disk = ServerInfo::disk();

        if ($disk['percent'] > 80) {
            return sprintf("disk空间超过%s%%", $disk['percent']);
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

    protected function checkXunSearch()
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

    protected function checkWebSocket()
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