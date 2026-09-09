<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

class CleanLogTask extends Task
{

    /**
     * @var array
     */
    protected array $whitelist = [];

    public function mainAction(): void
    {
        $this->cleanCommonLog();
        $this->cleanConsoleLog();
        $this->cleanHttpLog();
        $this->cleanSqlLog();
        $this->cleanRollbackLog();
        $this->cleanListenLog();
        $this->cleanMailLog();
        $this->cleanSmsLog();
        $this->cleanVodLog();
        $this->cleanStorageLog();
        $this->cleanPayLog();
        $this->cleanOrderLog();
        $this->cleanTradeLog();
        $this->cleanRefundLog();
        $this->cleanDeliverLog();
        $this->cleanQueueLog();
        $this->cleanOtherLog();
    }

    /**
     * 清理通用日志
     */
    protected function cleanCommonLog(): void
    {
        $type = 'common';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理Http日志
     */
    protected function cleanHttpLog(): void
    {
        $type = 'http';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理Console日志
     */
    protected function cleanConsoleLog(): void
    {
        $type = 'console';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理SQL日志
     */
    protected function cleanSqlLog(): void
    {
        $type = 'sql';

        $this->cleanLog($type, 3);

        $this->whitelist[] = $type;
    }

    /**
     * 清理Rollback日志
     */
    protected function cleanRollbackLog(): void
    {
        $type = 'rollback';

        $this->cleanLog($type, 14);

        $this->whitelist[] = $type;
    }

    /**
     * 清理监听日志
     */
    protected function cleanListenLog(): void
    {
        $type = 'listen';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理点播服务日志
     */
    protected function cleanVodLog(): void
    {
        $type = 'vod';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理存储服务日志
     */
    protected function cleanStorageLog(): void
    {
        $type = 'storage';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理短信服务日志
     */
    protected function cleanSmsLog(): void
    {
        $type = 'sms';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理邮件服务日志
     */
    protected function cleanMailLog(): void
    {
        $type = 'mail';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理支付服务日志
     */
    protected function cleanPayLog(): void
    {
        $type = 'pay';

        $this->cleanLog($type, 30);

        $this->whitelist[] = $type;
    }

    /**
     * 清理订单日志
     */
    protected function cleanOrderLog(): void
    {
        $type = 'order';

        $this->cleanLog($type, 30);

        $this->whitelist[] = $type;
    }

    /**
     * 清理交易日志
     */
    protected function cleanTradeLog(): void
    {
        $type = 'trade';

        $this->cleanLog($type, 30);

        $this->whitelist[] = $type;
    }

    /**
     * 清理退款日志
     */
    protected function cleanRefundLog(): void
    {
        $type = 'refund';

        $this->cleanLog($type, 30);

        $this->whitelist[] = $type;
    }

    /**
     * 清理发货日志
     */
    protected function cleanDeliverLog(): void
    {
        $type = 'deliver';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理队列日志
     */
    protected function cleanQueueLog(): void
    {
        $type = 'queue';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理其它日志
     *
     * @param int $keepDays
     */
    protected function cleanOtherLog(int $keepDays = 7): void
    {
        $files = glob(log_path() . "/*.log");

        if (!$files) return;

        foreach ($files as $file) {
            $name = str_replace(log_path() . '/', '', $file);
            $type = substr($name, 0, -15);
            $date = substr($name, -14, 10);
            $today = date('Y-m-d');
            if (in_array($type, $this->whitelist)) {
                continue;
            }
            if (strtotime($today) - strtotime($date) >= $keepDays * 86400) {
                $deleted = unlink($file);
                if ($deleted) {
                    $this->successPrint("remove {$file} success");
                } else {
                    $this->errorPrint("remove {$file} failed");
                }
            }
        }
    }

    /**
     * 清理日志文件
     *
     * @param string $prefix
     * @param int $keepDays 保留天数
     */
    protected function cleanLog(string $prefix, int $keepDays = 7): void
    {
        $files = glob(log_path() . "/{$prefix}-*.log");

        if (!$files) return;

        foreach ($files as $file) {
            $date = substr($file, -14, 10);
            $today = date('Y-m-d');
            if (strtotime($today) - strtotime($date) >= $keepDays * 86400) {
                $deleted = unlink($file);
                if ($deleted) {
                    $this->successPrint("remove {$file} success");
                } else {
                    $this->errorPrint("remove {$file} failed");
                }
            }
        }
    }

}
