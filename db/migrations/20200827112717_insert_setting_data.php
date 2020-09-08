<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InsertSettingData extends AbstractMigration
{

    public function up()
    {
        $rows = [
            [
                'section' => 'captcha',
                'item_key' => 'enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'captcha',
                'item_key' => 'app_id',
                'item_value' => '',
            ],
            [
                'section' => 'captcha',
                'item_key' => 'secret_key',
                'item_value' => '',
            ],
            [
                'section' => 'im.cs',
                'item_key' => 'enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'im.cs',
                'item_key' => 'user1_id',
                'item_value' => '',
            ],
            [
                'section' => 'im.cs',
                'item_key' => 'user2_id',
                'item_value' => '',
            ],
            [
                'section' => 'im.cs',
                'item_key' => 'user3_id',
                'item_value' => '',
            ],
            [
                'section' => 'im.main',
                'item_key' => 'title',
                'item_value' => '菜鸟驿站',
            ],
            [
                'section' => 'im.main',
                'item_key' => 'msg_max_length',
                'item_value' => '1000',
            ],
            [
                'section' => 'im.main',
                'item_key' => 'upload_img_enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'im.main',
                'item_key' => 'upload_file_enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'im.main',
                'item_key' => 'tool_audio_enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'im.main',
                'item_key' => 'tool_video_enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'live.push',
                'item_key' => 'domain',
                'item_value' => '',
            ],
            [
                'section' => 'live.push',
                'item_key' => 'auth_enabled',
                'item_value' => '1',
            ],
            [
                'section' => 'live.push',
                'item_key' => 'auth_key',
                'item_value' => '',
            ],
            [
                'section' => 'live.push',
                'item_key' => 'auth_delta',
                'item_value' => '18000',
            ],
            [
                'section' => 'live.pull',
                'item_key' => 'protocol',
                'item_value' => 'http',
            ],
            [
                'section' => 'live.pull',
                'item_key' => 'domain',
                'item_value' => '',
            ],
            [
                'section' => 'live.pull',
                'item_key' => 'trans_enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'live.pull',
                'item_key' => 'auth_enabled',
                'item_value' => '1',
            ],
            [
                'section' => 'live.pull',
                'item_key' => 'auth_key',
                'item_value' => '',
            ],
            [
                'section' => 'live.pull',
                'item_key' => 'auth_delta',
                'item_value' => '18000',
            ],
            [
                'section' => 'live.notify',
                'item_key' => 'auth_key',
                'item_value' => '',
            ],
            [
                'section' => 'live.notify',
                'item_key' => 'stream_begin_url',
                'item_value' => '',
            ],
            [
                'section' => 'live.notify',
                'item_key' => 'stream_end_url',
                'item_value' => '',
            ],
            [
                'section' => 'live.notify',
                'item_key' => 'record_url',
                'item_value' => '',
            ],
            [
                'section' => 'live.notify',
                'item_key' => 'snapshot_url',
                'item_value' => '',
            ],
            [
                'section' => 'live.notify',
                'item_key' => 'porn_url',
                'item_value' => '',
            ],
            [
                'section' => 'mailer',
                'item_key' => 'smtp_host',
                'item_value' => 'smtp.163.com',
            ],
            [
                'section' => 'mailer',
                'item_key' => 'smtp_port',
                'item_value' => '465',
            ],
            [
                'section' => 'mailer',
                'item_key' => 'smtp_encryption',
                'item_value' => 'ssl',
            ],
            [
                'section' => 'mailer',
                'item_key' => 'smtp_authentication',
                'item_value' => '1',
            ],
            [
                'section' => 'mailer',
                'item_key' => 'smtp_username',
                'item_value' => 'abc@163.com',
            ],
            [
                'section' => 'mailer',
                'item_key' => 'smtp_password',
                'item_value' => '888888',
            ],
            [
                'section' => 'mailer',
                'item_key' => 'smtp_from_email',
                'item_value' => 'abc@163.com',
            ],
            [
                'section' => 'mailer',
                'item_key' => 'smtp_from_name',
                'item_value' => 'ABC有限公司',
            ],
            [
                'section' => 'pay.alipay',
                'item_key' => 'enabled',
                'item_value' => '1',
            ],
            [
                'section' => 'pay.alipay',
                'item_key' => 'app_id',
                'item_value' => '',
            ],
            [
                'section' => 'pay.alipay',
                'item_key' => 'public_key',
                'item_value' => '',
            ],
            [
                'section' => 'pay.alipay',
                'item_key' => 'private_key',
                'item_value' => '',
            ],
            [
                'section' => 'pay.alipay',
                'item_key' => 'return_url',
                'item_value' => '',
            ],
            [
                'section' => 'pay.alipay',
                'item_key' => 'notify_url',
                'item_value' => '',
            ],
            [
                'section' => 'pay.wxpay',
                'item_key' => 'enabled',
                'item_value' => '1',
            ],
            [
                'section' => 'pay.wxpay',
                'item_key' => 'app_id',
                'item_value' => '',
            ],
            [
                'section' => 'pay.wxpay',
                'item_key' => 'mch_id',
                'item_value' => '',
            ],
            [
                'section' => 'pay.wxpay',
                'item_key' => 'key',
                'item_value' => '',
            ],
            [
                'section' => 'pay.wxpay',
                'item_key' => 'notify_url',
                'item_value' => '',
            ],
            [
                'section' => 'pay.wxpay',
                'item_key' => 'return_url',
                'item_value' => '',
            ],
            [
                'section' => 'secret',
                'item_key' => 'secret_key',
                'item_value' => '',
            ],
            [
                'section' => 'secret',
                'item_key' => 'secret_id',
                'item_value' => '',
            ],
            [
                'section' => 'secret',
                'item_key' => 'app_id',
                'item_value' => '',
            ],
            [
                'section' => 'site',
                'item_key' => 'title',
                'item_value' => '酷瓜云课堂',
            ],
            [
                'section' => 'site',
                'item_key' => 'keywords',
                'item_value' => '开源网课系统，开源网校系统，开源网络教育平台，开源在线教育平台',
            ],
            [
                'section' => 'site',
                'item_key' => 'description',
                'item_value' => '酷瓜云课堂，依托腾讯云基础服务，使用C扩展框架PHALCON开发',
            ],
            [
                'section' => 'site',
                'item_key' => 'url',
                'item_value' => '',
            ],
            [
                'section' => 'site',
                'item_key' => 'enabled',
                'item_value' => '1',
            ],
            [
                'section' => 'site',
                'item_key' => 'closed_tips',
                'item_value' => '站点维护中，请稍后再访问。',
            ],
            [
                'section' => 'site',
                'item_key' => 'copyright',
                'item_value' => '2016-2020 深圳市酷瓜软件有限公司',
            ],
            [
                'section' => 'site',
                'item_key' => 'icp_sn',
                'item_value' => '',
            ],
            [
                'section' => 'site',
                'item_key' => 'icp_link',
                'item_value' => 'http://www.miitbeian.gov.cn',
            ],
            [
                'section' => 'site',
                'item_key' => 'police_sn',
                'item_value' => '',
            ],
            [
                'section' => 'site',
                'item_key' => 'police_link',
                'item_value' => 'http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=abc',
            ],
            [
                'section' => 'site',
                'item_key' => 'analytics',
                'item_value' => '',
            ],
            [
                'section' => 'smser',
                'item_key' => 'app_id',
                'item_value' => '',
            ],
            [
                'section' => 'smser',
                'item_key' => 'app_key',
                'item_value' => '',
            ],
            [
                'section' => 'smser',
                'item_key' => 'signature',
                'item_value' => '酷瓜云课堂',
            ],
            [
                'section' => 'smser',
                'item_key' => 'template',
                'item_value' => '{"verify":"561282","order":"561954","refund":"561286","live":"561288"}',
            ],
            [
                'section' => 'cos',
                'item_key' => 'bucket',
                'item_value' => '',
            ],
            [
                'section' => 'cos',
                'item_key' => 'region',
                'item_value' => '',
            ],
            [
                'section' => 'cos',
                'item_key' => 'protocol',
                'item_value' => 'https',
            ],
            [
                'section' => 'cos',
                'item_key' => 'domain',
                'item_value' => '',
            ],
            [
                'section' => 'vod',
                'item_key' => 'storage_type',
                'item_value' => 'nearby',
            ],
            [
                'section' => 'vod',
                'item_key' => 'storage_region',
                'item_value' => '',
            ],
            [
                'section' => 'vod',
                'item_key' => 'audio_format',
                'item_value' => 'mp3',
            ],
            [
                'section' => 'vod',
                'item_key' => 'video_format',
                'item_value' => 'hls',
            ],
            [
                'section' => 'vod',
                'item_key' => 'watermark_enabled',
                'item_value' => '1',
            ],
            [
                'section' => 'vod',
                'item_key' => 'watermark_template',
                'item_value' => '462027',
            ],
            [
                'section' => 'vod',
                'item_key' => 'protocol',
                'item_value' => 'https',
            ],
            [
                'section' => 'vod',
                'item_key' => 'domain',
                'item_value' => '',
            ],
            [
                'section' => 'vod',
                'item_key' => 'key_anti_enabled',
                'item_value' => '1',
            ],
            [
                'section' => 'vod',
                'item_key' => 'key_anti_key',
                'item_value' => '',
            ],
            [
                'section' => 'vod',
                'item_key' => 'key_anti_expiry',
                'item_value' => '10800',
            ],
            [
                'section' => 'vod',
                'item_key' => 'key_anti_ip_limit',
                'item_value' => '',
            ],
        ];

        $this->table('kg_setting')->insert($rows)->save();
    }

    public function down()
    {
        $this->execute('DELETE FROM kg_setting');
    }

}