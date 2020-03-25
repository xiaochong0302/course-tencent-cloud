<?php

namespace App\Console\Tasks;

use Phalcon\Cli\Task;

class CleanLogTask extends Task
{

    public function mainAction()
    {
        $this->cleanCommonLog();
        $this->cleanConsoleLog();
        $this->cleanHttpLog();
        $this->cleanSqlLog();
        $this->cleanListenerLog();
        $this->cleanCaptchaLog();
        $this->cleanMailerLog();
        $this->cleanSmserLog();
        $this->cleanVodLog();
        $this->cleanStorageLog();
        $this->cleanAlipayLog();
        $this->cleanWxpayLog();
        $this->cleanRefundLog();
    }

    /**
     * 清理通用日志
     */
    protected function cleanCommonLog()
    {
        $this->cleanLog('common', 7);
    }

    /**
     * 清理Http日志
     */
    protected function cleanHttpLog()
    {
        $this->cleanLog('http', 7);
    }

    /**
     * 清理Console日志
     */
    protected function cleanConsoleLog()
    {
        $this->cleanLog('console', 7);
    }

    /**
     * 清理SQL日志
     */
    protected function cleanSqlLog()
    {
        $this->cleanLog('sql', 3);
    }

    /**
     * 清理监听者日志
     */
    protected function cleanListenerLog()
    {
        $this->cleanLog('listener', 7);
    }

    /**
     * 清理验证码服务日志
     */
    protected function cleanCaptchaLog()
    {
        $this->cleanLog('captcha', 7);
    }

    /**
     * 清理点播服务日志
     */
    protected function cleanVodLog()
    {
        $this->cleanLog('vod', 7);
    }

    /**
     * 清理存储服务日志
     */
    protected function cleanStorageLog()
    {
        $this->cleanLog('storage', 7);
    }

    /**
     * 清理短信服务日志
     */
    protected function cleanSmserLog()
    {
        $this->cleanLog('smser', 7);
    }

    /**
     * 清理邮件服务日志
     */
    protected function cleanMailerLog()
    {
        $this->cleanLog('mailer', 7);
    }

    /**
     * 清理阿里支付服务日志
     */
    protected function cleanAlipayLog()
    {
        $this->cleanLog('alipay', 30);
    }

    /**
     * 清理微信支付服务日志
     */
    protected function cleanWxpayLog()
    {
        $this->cleanLog('wxpay', 30);
    }

    /**
     * 清理退款日志
     */
    protected function cleanRefundLog()
    {
        $this->cleanLog('refund', 30);
    }

    /**
     * 清理日志文件
     *
     * @param string $prefix
     * @param int $keepDays 保留天数
     * @return mixed
     */
    protected function cleanLog($prefix, $keepDays)
    {
        $files = glob(log_path() . "/{$prefix}-*.log");

        if (!$files) return false;

        foreach ($files as $file) {
            $date = substr($file, -14, 10);
            $today = date('Y-m-d');
            if (strtotime($today) - strtotime($date) >= $keepDays * 86400) {
                $deleted = unlink($file);
                if ($deleted) {
                    echo "Delete {$file} success" . PHP_EOL;
                } else {
                    echo "Delete {$file} failed" . PHP_EOL;
                }
            }
        }
    }

}
