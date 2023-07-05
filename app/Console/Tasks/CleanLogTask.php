<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Console\Tasks;

class CleanLogTask extends Task
{

    protected $whitelist = [];

    public function mainAction()
    {
        $this->cleanCommonLog();
        $this->cleanConsoleLog();
        $this->cleanHttpLog();
        $this->cleanSqlLog();
        $this->cleanListenLog();
        $this->cleanWeChatLog();
        $this->cleanMailLog();
        $this->cleanSmsLog();
        $this->cleanVodLog();
        $this->cleanLiveLog();
        $this->cleanStorageLog();
        $this->cleanAlipayLog();
        $this->cleanWxpayLog();
        $this->cleanOrderLog();
        $this->cleanRefundLog();
        $this->cleanPointLog();
        $this->cleanDingTalkLog();
        $this->cleanNoticeLog();
        $this->cleanOtherLog();
    }

    /**
     * 清理通用日志
     */
    protected function cleanCommonLog()
    {
        $type = 'common';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理Http日志
     */
    protected function cleanHttpLog()
    {
        $type = 'http';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理Console日志
     */
    protected function cleanConsoleLog()
    {
        $type = 'console';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理SQL日志
     */
    protected function cleanSqlLog()
    {
        $type = 'sql';

        $this->cleanLog($type, 3);

        $this->whitelist[] = $type;
    }

    /**
     * 清理监听日志
     */
    protected function cleanListenLog()
    {
        $type = 'listen';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理点播服务日志
     */
    protected function cleanVodLog()
    {
        $type = 'vod';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理直播服务日志
     */
    protected function cleanLiveLog()
    {
        $type = 'live';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理存储服务日志
     */
    protected function cleanStorageLog()
    {
        $type = 'storage';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理短信服务日志
     */
    protected function cleanSmsLog()
    {
        $type = 'sms';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理邮件服务日志
     */
    protected function cleanMailLog()
    {
        $type = 'mail';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理微信服务日志
     */
    protected function cleanWeChatLog()
    {
        $type = 'wechat';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理阿里支付服务日志
     */
    protected function cleanAlipayLog()
    {
        $type = 'alipay';

        $this->cleanLog($type, 30);

        $this->whitelist[] = $type;
    }

    /**
     * 清理微信支付服务日志
     */
    protected function cleanWxpayLog()
    {
        $type = 'wxpay';

        $this->cleanLog($type, 30);

        $this->whitelist[] = $type;
    }

    /**
     * 清理订单日志
     */
    protected function cleanOrderLog()
    {
        $type = 'order';

        $this->cleanLog($type, 30);

        $this->whitelist[] = $type;
    }

    /**
     * 清理退款日志
     */
    protected function cleanRefundLog()
    {
        $type = 'refund';

        $this->cleanLog($type, 30);

        $this->whitelist[] = $type;
    }

    /**
     * 清理积分日志
     */
    protected function cleanPointLog()
    {
        $type = 'point';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理钉钉日志
     */
    protected function cleanDingTalkLog()
    {
        $type = 'dingtalk';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理通知日志
     */
    protected function cleanNoticeLog()
    {
        $type = 'notice';

        $this->cleanLog($type, 7);

        $this->whitelist[] = $type;
    }

    /**
     * 清理其它日志
     *
     * @param int $keepDays
     */
    protected function cleanOtherLog($keepDays = 7)
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
    protected function cleanLog($prefix, $keepDays)
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
