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
                'item_key' => 'app_id',
                'item_value' => '',
            ],
            [
                'section' => 'captcha',
                'item_key' => 'secret_key',
                'item_value' => '',
            ],
            [
                'section' => 'captcha',
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
                'item_key' => 'enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'im.cs',
                'item_key' => 'user3_id',
                'item_value' => '',
            ],
            [
                'section' => 'im.cs',
                'item_key' => 'user2_id',
                'item_value' => '',
            ],
            [
                'section' => 'im.main',
                'item_key' => 'msg_max_length',
                'item_value' => '1000',
            ],
            [
                'section' => 'im.main',
                'item_key' => 'title',
                'item_value' => '菜鸟驿站',
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
                'section' => 'live',
                'item_key' => 'push_domain',
                'item_value' => 'push.abc.com',
            ],
            [
                'section' => 'live',
                'item_key' => 'pull_trans_enabled',
                'item_value' => '1',
            ],
            [
                'section' => 'live',
                'item_key' => 'pull_auth_enabled',
                'item_value' => '1',
            ],
            [
                'section' => 'live',
                'item_key' => 'push_auth_enabled',
                'item_value' => '1',
            ],
            [
                'section' => 'live',
                'item_key' => 'pull_protocol',
                'item_value' => 'http',
            ],
            [
                'section' => 'live',
                'item_key' => 'push_auth_delta',
                'item_value' => '18000',
            ],
            [
                'section' => 'live',
                'item_key' => 'pull_auth_delta',
                'item_value' => '18000',
            ],
            [
                'section' => 'live',
                'item_key' => 'pull_auth_key',
                'item_value' => '',
            ],
            [
                'section' => 'live',
                'item_key' => 'pull_domain',
                'item_value' => 'play.abc.com',
            ],
            [
                'section' => 'live',
                'item_key' => 'push_auth_key',
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
                'section' => 'mailer',
                'item_key' => 'smtp_authentication',
                'item_value' => '1',
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
                'item_key' => 'enabled',
                'item_value' => '1',
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
                'section' => 'pay.alipay',
                'item_key' => 'app_id',
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
                'item_key' => 'enabled',
                'item_value' => '1',
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
                'item_key' => 'keywords',
                'item_value' => '开源网课系统，开源网校系统，开源网络教育平台，开源在线教育平台',
            ],
            [
                'section' => 'site',
                'item_key' => 'analytics',
                'item_value' => '',
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
                'item_key' => 'copyright',
                'item_value' => '2016-2020 深圳市酷瓜软件有限公司',
            ],
            [
                'section' => 'site',
                'item_key' => 'base_url',
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
                'item_key' => 'description',
                'item_value' => '酷瓜云课堂，依托腾讯云基础服务，使用C扩展框架PHALCON开发',
            ],
            [
                'section' => 'site',
                'item_key' => 'title',
                'item_value' => '酷瓜云课堂',
            ],
            [
                'section' => 'smser',
                'item_key' => 'app_id',
                'item_value' => '',
            ],
            [
                'section' => 'smser',
                'item_key' => 'template',
                'item_value' => '{"verify":{"id":"561282","content":"验证码：{1}，{2} 分钟内有效，如非本人操作请忽略。"},"order":{"id":"561954","content":"下单成功，商品名称：{1}，订单序号：{2}，订单金额：￥{3}"},"refund":{"id":"561286","content":"退款成功，商品名称：{1}，订单序号：{2}，退款金额：￥{3}"},"live":{"id":"561288","content":"直播预告，课程名称：{1}，章节名称：{2}，开播时间：{3}"}}',
            ],
            [
                'section' => 'smser',
                'item_key' => 'signature',
                'item_value' => 'abc',
            ],
            [
                'section' => 'smser',
                'item_key' => 'app_key',
                'item_value' => '',
            ],
            [
                'section' => 'storage',
                'item_key' => 'ci_protocol',
                'item_value' => 'https',
            ],
            [
                'section' => 'storage',
                'item_key' => 'bucket_name',
                'item_value' => '',
            ],
            [
                'section' => 'storage',
                'item_key' => 'ci_domain',
                'item_value' => '',
            ],
            [
                'section' => 'storage',
                'item_key' => 'bucket_region',
                'item_value' => '',
            ],
            [
                'section' => 'storage',
                'item_key' => 'bucket_protocol',
                'item_value' => 'https',
            ],
            [
                'section' => 'storage',
                'item_key' => 'bucket_domain',
                'item_value' => '',
            ],
            [
                'section' => 'vod',
                'item_key' => 'dist_protocol',
                'item_value' => 'https',
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
                'item_key' => 'key_anti_ip_limit',
                'item_value' => '',
            ],
            [
                'section' => 'vod',
                'item_key' => 'dist_domain',
                'item_value' => '',
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
                'item_key' => 'key_anti_enabled',
                'item_value' => '1',
            ],
        ];

        $this->table('kg_setting')->insert($rows)->save();
    }

    public function down()
    {
        $this->execute('DELETE FROM kg_setting');
    }

}