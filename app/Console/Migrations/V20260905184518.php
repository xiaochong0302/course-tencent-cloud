<?php
/**
 * @copyright Copyright (c) 2023 深圳市酷瓜软件有限公司
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link https://www.koogua.com
 */

namespace App\Console\Migrations;

use App\Console\Tasks\UpgradeTask;

class V20260905184518 extends Migration
{

    public function run(): void
    {
        $this->initSecretSettings();
        $this->initSiteSettings();
        $this->initMailSettings();
        $this->initSmsSettings();
        $this->initCosSettings();
        $this->initVodSettings();
        $this->initPaySettings();
        $this->initSecuritySettings();
        $this->initContactSettings();
    }

    protected function initSecretSettings(): void
    {
        $settings = [
            'app_id' => 'xxx',
            'secret_id' => 'xxx',
            'secret_key' => 'xxx',
        ];

        $this->saveSettings('secret', $settings);
    }

    protected function initSiteSettings(): void
    {
        $settings = [
            'analytics_enabled' => '0',
            'analytics_script' => '',
            'closed_tips' => '站点维护中，请稍后再访问！',
            'company_sn' => '',
            'company_sn_link' => '',
            'copyright' => 'xxx有限公司',
            'description' => '',
            'favicon' => '',
            'icp_link' => 'https://beian.miit.gov.cn',
            'icp_sn' => '',
            'icp_op_link' => 'https://beian.miit.gov.cn',
            'icp_op_sn' => '',
            'index_tpl_type' => 'simple',
            'keywords' => '',
            'license' => '',
            'logo' => '',
            'police_link' => '',
            'police_sn' => '',
            'status' => 'normal',
            'title' => '酷瓜云课堂',
            'url' => '',
        ];

        $this->saveSettings('site', $settings);
    }

    protected function initMailSettings(): void
    {
        $settings = [
            'smtp_host' => '',
            'smtp_port' => '465',
            'smtp_username' => '',
            'smtp_password' => '',
            'smtp_from_email' => '',
            'smtp_from_name' => '',
            'smtp_auth_enabled' => '1',
            'smtp_encryption' => 'ssl',
        ];

        $this->saveSettings('mail', $settings);
    }

    protected function initSmsSettings(): void
    {
        $settings = [
            'app_id' => '',
            'app_key' => '',
            'region' => 'ap-guangzhou',
            'signature' => '',
            'template' => [
                'verify' => [
                    'enabled' => '1',
                    'id' => '0',
                ],
            ],
        ];

        $this->saveSettings('sms', $settings);
    }

    protected function initCosSettings(): void
    {
        $settings = [
            'region' => 'ap-guangzhou',
            'protocol' => 'https',
            'domain' => '',
            'bucket' => '',
        ];

        $this->saveSettings('cos', $settings);
    }

    protected function initVodSettings(): void
    {
        $settings = [
            'audio_format' => 'mp3',
            'audio_quality' => [
                'sd',
            ],
            'domain' => '',
            'keep_origin_media' => '0',
            'key_anti_enabled' => '1',
            'key_anti_expiry' => '10800',
            'key_anti_ip_limit' => '3',
            'key_anti_key' => '',
            'protocol' => 'https',
            'std_trans_enabled' => '1',
            'storage_region' => '',
            'storage_type' => 'nearby',
            'sub_app_id' => '',
            'transcode_type' => 'normal',
            'video_format' => 'hls',
            'video_quality' => [
                'sd',
            ],
            'wmk_enabled' => '1',
            'wmk_tpl_id' => '843994',
        ];

        $this->saveSettings('vod', $settings);
    }

    protected function initPaySettings(): void
    {
        $settings = [
            'enabled' => '1',
            'app_id' => '',
            'app_secret_cert' => '',
            'notify_url' => '',
            'return_url' => '',
            'service_rate' => '10',
        ];

        $this->saveSettings('pay.alipay', $settings);

        $settings = [
            'enabled' => '1',
            'app_id' => '',
            'mch_id' => '',
            'mch_secret_key' => '',
            'mp_app_id' => '',
            'mini_app_id' => '',
            'wechat_public_key_id' => '',
            'notify_url' => '',
            'return_url' => '',
            'service_rate' => '10',
        ];

        $this->saveSettings('pay.wxpay', $settings);
    }

    protected function initSecuritySettings(): void
    {
        $settings = [
            'private' => '0',
            'failed_login_limit' => '5',
            'failed_login_lock' => '600',
            'mutex_client_limit' => '1',
            'mutex_login' => '1',
        ];

        $this->saveSettings('security.access', $settings);

        $settings = [
            'register_with_phone' => '1',
            'register_with_email' => '1',
        ];

        $this->saveSettings('security.register', $settings);

        $settings = [
            'enabled' => '1',
            'interval' => '900',
            'rate_limit' => '900',
        ];

        $this->saveSettings('security.throttle', $settings);

        $settings = [
            'enabled' => '0',
            'content' => '',
        ];

        $this->saveSettings('security.blacklist', $settings);
    }

    protected function initContactSettings(): void
    {
        $settings = [
            'enabled' => '1',
            'phone' => '',
            'email' => '',
            'address' => '',
            'weibo' => '',
            'zhihu' => '',
            'douyin' => '',
            'toutiao' => '',
            'wechat' => '',
            'qq' => '',
        ];

        $this->saveSettings('contact', $settings);
    }

}
