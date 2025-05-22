<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

use Phinx\Migration\AbstractMigration;

final class V20210403184518 extends AbstractMigration
{

    public function up()
    {
        $this->initSettingData();
        $this->initUserData();
        $this->initRoleData();
        $this->initNavData();
        $this->initVipData();
    }

    protected function initUserData()
    {
        $now = time();

        $account = [
            'id' => 10000,
            'email' => '10000@163.com',
            'password' => '1a1e4568f1a3740b8853a8a16e29bc87',
            'salt' => 'MbZWxN3L',
            'create_time' => $now,
        ];

        $this->table('kg_account')->insert($account)->saveData();

        $user = [
            'id' => $account['id'],
            'name' => '酷瓜云课堂',
            'avatar' => '/img/default/user_avatar.png',
            'title' => '官方人员',
            'about' => '酷瓜云课堂，开源在线教育解决方案',
            'admin_role' => 1,
            'edu_role' => 2,
            'create_time' => $now,
        ];

        $this->table('kg_user')->insert($user)->saveData();

        $balance = [
            'user_id' => $account['id'],
            'create_time' => $now,
        ];

        $this->table('kg_user_balance')->insert($balance)->saveData();
    }

    protected function initRoleData()
    {
        $now = time();

        $rows = [
            [
                'id' => 1,
                'type' => 1,
                'name' => '管理员',
                'summary' => '管理员',
                'routes' => '[]',
                'user_count' => 1,
                'create_time' => $now,
            ],
            [
                'id' => 2,
                'type' => 1,
                'name' => '运营',
                'summary' => '运营人员',
                'routes' => '[]',
                'user_count' => 0,
                'create_time' => $now,
            ],
            [
                'id' => 3,
                'type' => 1,
                'name' => '编辑',
                'summary' => '编辑人员',
                'routes' => '[]',
                'user_count' => 0,
                'create_time' => $now,
            ],
            [
                'id' => 4,
                'type' => 1,
                'name' => '财务',
                'summary' => '财务人员',
                'routes' => '[]',
                'user_count' => 0,
                'create_time' => $now,
            ],
        ];

        $this->table('kg_role')->insert($rows)->save();
    }

    protected function initNavData()
    {
        $now = time();

        $rows = [
            [
                'id' => 1,
                'parent_id' => 0,
                'level' => 1,
                'name' => '首页',
                'path' => ',1,',
                'target' => '_self',
                'url' => '/',
                'position' => 1,
                'priority' => 1,
                'published' => 1,
                'create_time' => $now,
            ],
            [
                'id' => 2,
                'parent_id' => 0,
                'level' => 1,
                'name' => '课程',
                'path' => ',2,',
                'target' => '_self',
                'url' => '/course/list',
                'position' => 1,
                'priority' => 2,
                'published' => 1,
                'create_time' => $now,
            ],
            [
                'id' => 3,
                'parent_id' => 0,
                'level' => 1,
                'name' => '专栏',
                'path' => ',3,',
                'target' => '_self',
                'url' => '/article/list',
                'position' => 1,
                'priority' => 3,
                'published' => 1,
                'create_time' => $now,
            ],
            [
                'id' => 4,
                'parent_id' => 0,
                'level' => 1,
                'name' => '问答',
                'path' => ',4,',
                'target' => '_self',
                'url' => '/question/list',
                'position' => 1,
                'priority' => 4,
                'published' => 1,
                'create_time' => $now,
            ],
            [
                'id' => 5,
                'parent_id' => 0,
                'level' => 1,
                'name' => '师资',
                'path' => ',5,',
                'target' => '_self',
                'url' => '/teacher/list',
                'position' => 1,
                'priority' => 5,
                'published' => 1,
                'create_time' => $now,
            ],
            [
                'id' => 7,
                'parent_id' => 0,
                'level' => 1,
                'name' => '秒杀',
                'path' => ',7,',
                'target' => '_self',
                'url' => '/flash/sale',
                'position' => 1,
                'priority' => 7,
                'published' => 1,
                'create_time' => $now,
            ],
            [
                'id' => 8,
                'parent_id' => 0,
                'level' => 1,
                'name' => '关于我们',
                'path' => ',8,',
                'target' => '_blank',
                'url' => '#',
                'position' => 2,
                'priority' => 1,
                'published' => 1,
                'create_time' => $now,
            ],
            [
                'id' => 9,
                'parent_id' => 0,
                'level' => 1,
                'name' => '联系我们',
                'path' => ',9,',
                'target' => '_blank',
                'url' => '#',
                'position' => 2,
                'priority' => 2,
                'published' => 1,
                'create_time' => $now,
            ],
            [
                'id' => 10,
                'parent_id' => 0,
                'level' => 1,
                'name' => '人才招聘',
                'path' => ',10,',
                'target' => '_blank',
                'url' => '#',
                'position' => 2,
                'priority' => 3,
                'published' => 1,
                'create_time' => $now,
            ],
            [
                'id' => 11,
                'parent_id' => 0,
                'level' => 1,
                'name' => '帮助中心',
                'path' => ',11,',
                'target' => '_blank',
                'url' => '/help',
                'position' => 2,
                'priority' => 4,
                'published' => 1,
                'create_time' => $now,
            ],
            [
                'id' => 12,
                'parent_id' => 0,
                'level' => 1,
                'name' => '友情链接',
                'path' => ',12,',
                'target' => '_blank',
                'url' => '#',
                'position' => 2,
                'priority' => 5,
                'published' => 1,
                'create_time' => $now,
            ],
        ];

        $this->table('kg_nav')->insert($rows)->save();
    }

    protected function initVipData()
    {
        $now = time();

        $rows = [
            [
                'id' => 1,
                'title' => '1个月',
                'expiry' => 1,
                'price' => 60.00,
                'create_time' => $now,
            ],
            [
                'id' => 2,
                'title' => '3个月',
                'expiry' => 3,
                'price' => 150.00,
                'create_time' => $now,
            ],
            [
                'id' => 3,
                'title' => '6个月',
                'expiry' => 6,
                'price' => 240.00,
                'create_time' => $now,
            ],
            [
                'id' => 4,
                'title' => '12个月',
                'expiry' => 12,
                'price' => 360.00,
                'create_time' => $now,
            ],
        ];

        $this->table('kg_vip')->insert($rows)->save();
    }

    protected function initSettingData()
    {
        $rows = [
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
                'section' => 'mail',
                'item_key' => 'smtp_host',
                'item_value' => '',
            ],
            [
                'section' => 'mail',
                'item_key' => 'smtp_port',
                'item_value' => '465',
            ],
            [
                'section' => 'mail',
                'item_key' => 'smtp_encryption',
                'item_value' => 'ssl',
            ],
            [
                'section' => 'mail',
                'item_key' => 'smtp_auth_enabled',
                'item_value' => '1',
            ],
            [
                'section' => 'mail',
                'item_key' => 'smtp_username',
                'item_value' => '',
            ],
            [
                'section' => 'mail',
                'item_key' => 'smtp_password',
                'item_value' => '',
            ],
            [
                'section' => 'mail',
                'item_key' => 'smtp_from_email',
                'item_value' => '',
            ],
            [
                'section' => 'mail',
                'item_key' => 'smtp_from_name',
                'item_value' => '',
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
                'section' => 'pay.alipay',
                'item_key' => 'service_rate',
                'item_value' => '5',
            ],
            [
                'section' => 'pay.wxpay',
                'item_key' => 'enabled',
                'item_value' => '1',
            ],
            [
                'section' => 'pay.wxpay',
                'item_key' => 'mp_app_id',
                'item_value' => '',
            ],
            [
                'section' => 'pay.wxpay',
                'item_key' => 'mini_app_id',
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
                'item_key' => 'service_rate',
                'item_value' => '5',
            ],
            [
                'section' => 'secret',
                'item_key' => 'secret_key',
                'item_value' => 'xxx',
            ],
            [
                'section' => 'secret',
                'item_key' => 'secret_id',
                'item_value' => 'xxx',
            ],
            [
                'section' => 'secret',
                'item_key' => 'app_id',
                'item_value' => 'xxx',
            ],
            [
                'section' => 'site',
                'item_key' => 'title',
                'item_value' => '酷瓜云课堂',
            ],
            [
                'section' => 'site',
                'item_key' => 'keywords',
                'item_value' => '开源网课系统，开源网校系统，开源知识付费系统，开源在线教育系统',
            ],
            [
                'section' => 'site',
                'item_key' => 'description',
                'item_value' => '酷瓜云课堂，依托腾讯云基础服务，使用C扩展框架PHALCON开发',
            ],
            [
                'section' => 'site',
                'item_key' => 'logo',
                'item_value' => '',
            ],
            [
                'section' => 'site',
                'item_key' => 'favicon',
                'item_value' => '',
            ],
            [
                'section' => 'site',
                'item_key' => 'url',
                'item_value' => '',
            ],
            [
                'section' => 'site',
                'item_key' => 'status',
                'item_value' => 'normal',
            ],
            [
                'section' => 'site',
                'item_key' => 'closed_tips',
                'item_value' => '站点维护中，请稍后再访问。',
            ],
            [
                'section' => 'site',
                'item_key' => 'index_tpl_type',
                'item_value' => 'simple',
            ],
            [
                'section' => 'site',
                'item_key' => 'copyright',
                'item_value' => 'XXX有限公司',
            ],
            [
                'section' => 'site',
                'item_key' => 'icp_sn',
                'item_value' => '',
            ],
            [
                'section' => 'site',
                'item_key' => 'icp_link',
                'item_value' => 'http://beian.miit.gov.cn',
            ],
            [
                'section' => 'site',
                'item_key' => 'police_sn',
                'item_value' => '',
            ],
            [
                'section' => 'site',
                'item_key' => 'police_link',
                'item_value' => '',
            ],
            [
                'section' => 'site',
                'item_key' => 'analytics_enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'site',
                'item_key' => 'analytics_script',
                'item_value' => '',
            ],
            [
                'section' => 'sms',
                'item_key' => 'app_id',
                'item_value' => '',
            ],
            [
                'section' => 'sms',
                'item_key' => 'app_key',
                'item_value' => '',
            ],
            [
                'section' => 'sms',
                'item_key' => 'signature',
                'item_value' => '',
            ],
            [
                'section' => 'sms',
                'item_key' => 'template',
                'item_value' => json_encode([
                    'verify' => ['enabled' => 1, 'id' => 0],
                    'order_finish' => ['enabled' => 0, 'id' => 0],
                    'refund_finish' => ['enabled' => 0, 'id' => 0],
                    'live_begin' => ['enabled' => 0, 'id' => 0],
                    'consult_reply' => ['enabled' => 0, 'id' => 0],
                    'goods_deliver' => ['enabled' => 0, 'id' => 0],
                ]),
            ],
            [
                'section' => 'cos',
                'item_key' => 'bucket',
                'item_value' => 'course-1255691183',
            ],
            [
                'section' => 'cos',
                'item_key' => 'region',
                'item_value' => 'ap-guangzhou',
            ],
            [
                'section' => 'cos',
                'item_key' => 'protocol',
                'item_value' => 'https',
            ],
            [
                'section' => 'cos',
                'item_key' => 'domain',
                'item_value' => 'course-1255691183.file.myqcloud.com',
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
                'item_key' => 'wmk_enabled',
                'item_value' => '1',
            ],
            [
                'section' => 'vod',
                'item_key' => 'wmk_tpl_id',
                'item_value' => '',
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
                'item_value' => '3',
            ],
            [
                'section' => 'dingtalk.robot',
                'item_key' => 'enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'dingtalk.robot',
                'item_key' => 'app_secret',
                'item_value' => '',
            ],
            [
                'section' => 'dingtalk.robot',
                'item_key' => 'app_token',
                'item_value' => '',
            ],
            [
                'section' => 'dingtalk.robot',
                'item_key' => 'ts_mobiles',
                'item_value' => '',
            ],
            [
                'section' => 'dingtalk.robot',
                'item_key' => 'cs_mobiles',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.qq',
                'item_key' => 'enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'oauth.qq',
                'item_key' => 'client_id',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.qq',
                'item_key' => 'client_secret',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.qq',
                'item_key' => 'redirect_uri',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.weixin',
                'item_key' => 'enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'oauth.weixin',
                'item_key' => 'client_id',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.weixin',
                'item_key' => 'client_secret',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.weixin',
                'item_key' => 'redirect_uri',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.weibo',
                'item_key' => 'enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'oauth.weibo',
                'item_key' => 'client_id',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.weibo',
                'item_key' => 'client_secret',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.weibo',
                'item_key' => 'redirect_uri',
                'item_value' => '',
            ],
            [
                'section' => 'oauth.weibo',
                'item_key' => 'refuse_uri',
                'item_value' => '',
            ],
            [
                'section' => 'wechat.oa',
                'item_key' => 'enabled',
                'item_value' => '0',
            ],
            [
                'section' => 'wechat.oa',
                'item_key' => 'app_id',
                'item_value' => '',
            ],
            [
                'section' => 'wechat.oa',
                'item_key' => 'app_secret',
                'item_value' => '',
            ],
            [
                'section' => 'wechat.oa',
                'item_key' => 'app_token',
                'item_value' => '',
            ],
            [
                'section' => 'wechat.oa',
                'item_key' => 'aes_key',
                'item_value' => '',
            ],
            [
                'section' => 'wechat.oa',
                'item_key' => 'notify_url',
                'item_value' => '',
            ],
            [
                'section' => 'wechat.oa',
                'item_key' => 'notice_template',
                'item_value' => json_encode([
                    'account_login' => ['enabled' => 0, 'id' => 0],
                    'order_finish' => ['enabled' => 0, 'id' => 0],
                    'refund_finish' => ['enabled' => 0, 'id' => 0],
                    'goods_deliver' => ['enabled' => 0, 'id' => 0],
                    'consult_reply' => ['enabled' => 0, 'id' => 0],
                    'live_begin' => ['enabled' => 0, 'id' => 0],
                ]),
            ],
            [
                'section' => 'wechat.oa',
                'item_key' => 'menu',
                'item_value' => json_encode([
                    [
                        'name' => '菜单1',
                        'url' => '',
                        'children' => [
                            [
                                'type' => 'view',
                                'name' => '菜单1-1',
                                'url' => 'https://gitee.com/koogua',
                            ],
                        ],
                    ],
                    [
                        'name' => '菜单2',
                        'url' => '',
                        'children' => [
                            [
                                'type' => 'view',
                                'name' => '菜单2-1',
                                'url' => 'https://gitee.com/koogua',
                            ],
                        ],
                    ],
                    [
                        'name' => '菜单3',
                        'url' => '',
                        'children' => [
                            [
                                'type' => 'view',
                                'name' => '菜单3-1',
                                'url' => 'https://gitee.com/koogua',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                'section' => 'point',
                'item_key' => 'enabled',
                'item_value' => 1,
            ],
            [
                'section' => 'point',
                'item_key' => 'consume_rule',
                'item_value' => json_encode(['enabled' => 1, 'rate' => 5]),
            ],
            [
                'section' => 'point',
                'item_key' => 'event_rule',
                'item_value' => json_encode([
                    'account_register' => ['enabled' => 1, 'point' => 100],
                    'course_review' => ['enabled' => 1, 'point' => 50],
                    'chapter_study' => ['enabled' => 1, 'point' => 10],
                    'site_visit' => ['enabled' => 1, 'point' => 10],
                    'article_post' => ['enabled' => 1, 'point' => 20, 'limit' => 50],
                    'question_post' => ['enabled' => 1, 'point' => 5, 'limit' => 50],
                    'answer_post' => ['enabled' => 1, 'point' => 5, 'limit' => 50],
                    'comment_post' => ['enabled' => 1, 'point' => 2, 'limit' => 10],
                    'article_liked' => ['enabled' => 1, 'point' => 1, 'limit' => 50],
                    'question_liked' => ['enabled' => 1, 'point' => 1, 'limit' => 50],
                    'answer_liked' => ['enabled' => 1, 'point' => 1, 'limit' => 50],
                ]),
            ],
        ];

        $this->table('kg_setting')->insert($rows)->save();
    }

}
