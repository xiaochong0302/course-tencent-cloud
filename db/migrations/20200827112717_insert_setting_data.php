<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InsertSettingData extends AbstractMigration
{

    public function up()
    {
        $rows = array(
            0 =>
                array(
                    'section' => 'captcha',
                    'item_key' => 'app_id',
                    'item_value' => '',
                ),
            1 =>
                array(
                    'section' => 'captcha',
                    'item_key' => 'secret_key',
                    'item_value' => '',
                ),
            2 =>
                array(
                    'section' => 'captcha',
                    'item_key' => 'enabled',
                    'item_value' => '0',
                ),
            3 =>
                array(
                    'section' => 'im.cs',
                    'item_key' => 'user1_id',
                    'item_value' => '',
                ),
            4 =>
                array(
                    'section' => 'im.cs',
                    'item_key' => 'enabled',
                    'item_value' => '0',
                ),
            5 =>
                array(
                    'section' => 'im.cs',
                    'item_key' => 'user3_id',
                    'item_value' => '',
                ),
            6 =>
                array(
                    'section' => 'im.cs',
                    'item_key' => 'user2_id',
                    'item_value' => '',
                ),
            7 =>
                array(
                    'section' => 'im.main',
                    'item_key' => 'msg_max_length',
                    'item_value' => '1000',
                ),
            8 =>
                array(
                    'section' => 'im.main',
                    'item_key' => 'title',
                    'item_value' => '菜鸟驿站',
                ),
            9 =>
                array(
                    'section' => 'im.main',
                    'item_key' => 'upload_img_enabled',
                    'item_value' => '0',
                ),
            10 =>
                array(
                    'section' => 'im.main',
                    'item_key' => 'upload_file_enabled',
                    'item_value' => '0',
                ),
            11 =>
                array(
                    'section' => 'im.main',
                    'item_key' => 'tool_audio_enabled',
                    'item_value' => '0',
                ),
            12 =>
                array(
                    'section' => 'im.main',
                    'item_key' => 'tool_video_enabled',
                    'item_value' => '0',
                ),
            13 =>
                array(
                    'section' => 'live',
                    'item_key' => 'push_domain',
                    'item_value' => 'push.abc.com',
                ),
            14 =>
                array(
                    'section' => 'live',
                    'item_key' => 'pull_trans_template',
                    'item_value' => '{"fd":{"id":"fd","bit_rate":"500","summary":"流畅","height":"540"},"sd":{"id":"sd","bit_rate":"1000","summary":"标清","height":"720"},"hd":{"id":"hd","bit_rate":"2000","summary":"高清","height":"1080"}}',
                ),
            15 =>
                array(
                    'section' => 'live',
                    'item_key' => 'pull_trans_enabled',
                    'item_value' => '1',
                ),
            16 =>
                array(
                    'section' => 'live',
                    'item_key' => 'pull_auth_enabled',
                    'item_value' => '1',
                ),
            17 =>
                array(
                    'section' => 'live',
                    'item_key' => 'push_auth_enabled',
                    'item_value' => '1',
                ),
            18 =>
                array(
                    'section' => 'live',
                    'item_key' => 'pull_protocol',
                    'item_value' => 'http',
                ),
            19 =>
                array(
                    'section' => 'live',
                    'item_key' => 'push_auth_delta',
                    'item_value' => '18000',
                ),
            20 =>
                array(
                    'section' => 'live',
                    'item_key' => 'pull_auth_delta',
                    'item_value' => '18000',
                ),
            21 =>
                array(
                    'section' => 'live',
                    'item_key' => 'pull_auth_key',
                    'item_value' => '',
                ),
            22 =>
                array(
                    'section' => 'live',
                    'item_key' => 'pull_domain',
                    'item_value' => 'play.abc.com',
                ),
            23 =>
                array(
                    'section' => 'live',
                    'item_key' => 'push_template',
                    'item_value' => '',
                ),
            24 =>
                array(
                    'section' => 'live',
                    'item_key' => 'push_auth_key',
                    'item_value' => '',
                ),
            25 =>
                array(
                    'section' => 'mailer',
                    'item_key' => 'smtp_host',
                    'item_value' => 'smtp.163.com',
                ),
            26 =>
                array(
                    'section' => 'mailer',
                    'item_key' => 'smtp_port',
                    'item_value' => '465',
                ),
            27 =>
                array(
                    'section' => 'mailer',
                    'item_key' => 'smtp_encryption',
                    'item_value' => 'ssl',
                ),
            28 =>
                array(
                    'section' => 'mailer',
                    'item_key' => 'smtp_username',
                    'item_value' => 'abc@163.com',
                ),
            29 =>
                array(
                    'section' => 'mailer',
                    'item_key' => 'smtp_password',
                    'item_value' => '888888',
                ),
            30 =>
                array(
                    'section' => 'mailer',
                    'item_key' => 'smtp_from_email',
                    'item_value' => 'abc@163.com',
                ),
            31 =>
                array(
                    'section' => 'mailer',
                    'item_key' => 'smtp_from_name',
                    'item_value' => 'ABC有限公司',
                ),
            32 =>
                array(
                    'section' => 'mailer',
                    'item_key' => 'smtp_authentication',
                    'item_value' => '1',
                ),
            33 =>
                array(
                    'section' => 'pay.alipay',
                    'item_key' => 'public_key',
                    'item_value' => '',
                ),
            34 =>
                array(
                    'section' => 'pay.alipay',
                    'item_key' => 'private_key',
                    'item_value' => '',
                ),
            35 =>
                array(
                    'section' => 'pay.alipay',
                    'item_key' => 'enabled',
                    'item_value' => '1',
                ),
            36 =>
                array(
                    'section' => 'pay.alipay',
                    'item_key' => 'return_url',
                    'item_value' => '',
                ),
            37 =>
                array(
                    'section' => 'pay.alipay',
                    'item_key' => 'notify_url',
                    'item_value' => '',
                ),
            38 =>
                array(
                    'section' => 'pay.alipay',
                    'item_key' => 'app_id',
                    'item_value' => '',
                ),
            39 =>
                array(
                    'section' => 'pay.wxpay',
                    'item_key' => 'notify_url',
                    'item_value' => '',
                ),
            40 =>
                array(
                    'section' => 'pay.wxpay',
                    'item_key' => 'return_url',
                    'item_value' => '',
                ),
            41 =>
                array(
                    'section' => 'pay.wxpay',
                    'item_key' => 'app_id',
                    'item_value' => '',
                ),
            42 =>
                array(
                    'section' => 'pay.wxpay',
                    'item_key' => 'mch_id',
                    'item_value' => '',
                ),
            43 =>
                array(
                    'section' => 'pay.wxpay',
                    'item_key' => 'key',
                    'item_value' => '',
                ),
            44 =>
                array(
                    'section' => 'pay.wxpay',
                    'item_key' => 'enabled',
                    'item_value' => '1',
                ),
            45 =>
                array(
                    'section' => 'secret',
                    'item_key' => 'secret_key',
                    'item_value' => '',
                ),
            46 =>
                array(
                    'section' => 'secret',
                    'item_key' => 'secret_id',
                    'item_value' => '',
                ),
            47 =>
                array(
                    'section' => 'secret',
                    'item_key' => 'app_id',
                    'item_value' => '',
                ),
            48 =>
                array(
                    'section' => 'site',
                    'item_key' => 'keywords',
                    'item_value' => '开源网课系统，开源网校系统，开源网络教育平台，开源在线教育平台',
                ),
            49 =>
                array(
                    'section' => 'site',
                    'item_key' => 'analytics',
                    'item_value' => '',
                ),
            50 =>
                array(
                    'section' => 'site',
                    'item_key' => 'icp_sn',
                    'item_value' => '',
                ),
            51 =>
                array(
                    'section' => 'site',
                    'item_key' => 'icp_link',
                    'item_value' => 'http://www.miitbeian.gov.cn',
                ),
            52 =>
                array(
                    'section' => 'site',
                    'item_key' => 'police_sn',
                    'item_value' => '',
                ),
            53 =>
                array(
                    'section' => 'site',
                    'item_key' => 'police_link',
                    'item_value' => 'http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=abc',
                ),
            54 =>
                array(
                    'section' => 'site',
                    'item_key' => 'copyright',
                    'item_value' => '2016-2020 深圳市酷瓜软件有限公司',
                ),
            55 =>
                array(
                    'section' => 'site',
                    'item_key' => 'base_url',
                    'item_value' => '',
                ),
            56 =>
                array(
                    'section' => 'site',
                    'item_key' => 'enabled',
                    'item_value' => '1',
                ),
            57 =>
                array(
                    'section' => 'site',
                    'item_key' => 'closed_tips',
                    'item_value' => '站点维护中，请稍后再访问。',
                ),
            58 =>
                array(
                    'section' => 'site',
                    'item_key' => 'description',
                    'item_value' => '酷瓜云课堂，依托腾讯云基础服务，使用C扩展框架PHALCON开发',
                ),
            59 =>
                array(
                    'section' => 'site',
                    'item_key' => 'title',
                    'item_value' => '酷瓜云课堂',
                ),
            60 =>
                array(
                    'section' => 'smser',
                    'item_key' => 'app_id',
                    'item_value' => '',
                ),
            61 =>
                array(
                    'section' => 'smser',
                    'item_key' => 'template',
                    'item_value' => '{"verify":{"id":"561282","content":"验证码：{1}，{2} 分钟内有效，如非本人操作请忽略。"},"order":{"id":"561954","content":"下单成功，商品名称：{1}，订单序号：{2}，订单金额：￥{3}"},"refund":{"id":"561286","content":"退款成功，商品名称：{1}，订单序号：{2}，退款金额：￥{3}"},"live":{"id":"561288","content":"直播预告，课程名称：{1}，章节名称：{2}，开播时间：{3}"}}',
                ),
            62 =>
                array(
                    'section' => 'smser',
                    'item_key' => 'signature',
                    'item_value' => 'abc',
                ),
            63 =>
                array(
                    'section' => 'smser',
                    'item_key' => 'app_key',
                    'item_value' => '',
                ),
            64 =>
                array(
                    'section' => 'storage',
                    'item_key' => 'ci_protocol',
                    'item_value' => 'https',
                ),
            65 =>
                array(
                    'section' => 'storage',
                    'item_key' => 'bucket_name',
                    'item_value' => '',
                ),
            66 =>
                array(
                    'section' => 'storage',
                    'item_key' => 'ci_domain',
                    'item_value' => '',
                ),
            67 =>
                array(
                    'section' => 'storage',
                    'item_key' => 'bucket_region',
                    'item_value' => '',
                ),
            68 =>
                array(
                    'section' => 'storage',
                    'item_key' => 'bucket_protocol',
                    'item_value' => 'https',
                ),
            69 =>
                array(
                    'section' => 'storage',
                    'item_key' => 'bucket_domain',
                    'item_value' => '',
                ),
            70 =>
                array(
                    'section' => 'vod',
                    'item_key' => 'dist_protocol',
                    'item_value' => 'https',
                ),
            71 =>
                array(
                    'section' => 'vod',
                    'item_key' => 'watermark_enabled',
                    'item_value' => '1',
                ),
            72 =>
                array(
                    'section' => 'vod',
                    'item_key' => 'watermark_template',
                    'item_value' => '462027',
                ),
            73 =>
                array(
                    'section' => 'vod',
                    'item_key' => 'video_template',
                    'item_value' => '100210,100220,100230',
                ),
            74 =>
                array(
                    'section' => 'vod',
                    'item_key' => 'audio_format',
                    'item_value' => 'mp3',
                ),
            75 =>
                array(
                    'section' => 'vod',
                    'item_key' => 'video_format',
                    'item_value' => 'hls',
                ),
            76 =>
                array(
                    'section' => 'vod',
                    'item_key' => 'storage_type',
                    'item_value' => 'nearby',
                ),
            77 =>
                array(
                    'section' => 'vod',
                    'item_key' => 'storage_region',
                    'item_value' => '',
                ),
            78 =>
                array(
                    'section' => 'vod',
                    'item_key' => 'template',
                    'item_value' => '',
                ),
            79 =>
                array(
                    'section' => 'vod',
                    'item_key' => 'key_anti_ip_limit',
                    'item_value' => '',
                ),
            80 =>
                array(
                    'section' => 'vod',
                    'item_key' => 'dist_domain',
                    'item_value' => '',
                ),
            81 =>
                array(
                    'section' => 'vod',
                    'item_key' => 'audio_template',
                    'item_value' => '1110',
                ),
            82 =>
                array(
                    'section' => 'vod',
                    'item_key' => 'key_anti_key',
                    'item_value' => '',
                ),
            83 =>
                array(
                    'section' => 'vod',
                    'item_key' => 'key_anti_expiry',
                    'item_value' => '10800',
                ),
            84 =>
                array(
                    'section' => 'vod',
                    'item_key' => 'key_anti_enabled',
                    'item_value' => '1',
                ),
        );

        $this->table('kg_setting')->insert($rows)->save();
    }

    public function down()
    {
        $this->execute('DELETE FROM kg_setting');
    }

}