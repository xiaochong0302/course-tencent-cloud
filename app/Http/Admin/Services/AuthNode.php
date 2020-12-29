<?php

namespace App\Http\Admin\Services;

class AuthNode extends Service
{

    public function getNodes()
    {
        $nodes = [];

        $nodes[] = $this->getContentNodes();
        $nodes[] = $this->getOperationNodes();
        $nodes[] = $this->getFinanceNodes();
        $nodes[] = $this->getUserNodes();
        $nodes[] = $this->getSettingNodes();
        $nodes[] = $this->getUtilNodes();

        return $nodes;
    }

    protected function getContentNodes()
    {
        return [
            'id' => '1',
            'title' => '内容管理',
            'children' => [
                [
                    'id' => '1-2',
                    'title' => '分类管理',
                    'type' => 'button',
                    'children' => [
                        [
                            'id' => '1-2-1',
                            'title' => '分类列表',
                            'type' => 'button',
                            'route' => 'admin.category.list',
                            'params' => ['type' => 1],
                        ],
                        [
                            'id' => '1-2-2',
                            'title' => '添加分类',
                            'type' => 'button',
                            'route' => 'admin.category.add',
                            'params' => ['type' => 1],
                        ],
                        [
                            'id' => '1-2-3',
                            'title' => '编辑分类',
                            'type' => 'button',
                            'route' => 'admin.category.edit',
                        ],
                        [
                            'id' => '1-2-4',
                            'title' => '删除分类',
                            'type' => 'button',
                            'route' => 'admin.category.delete',
                        ],
                    ],
                ],
                [
                    'id' => '1-1',
                    'title' => '课程管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '1-1-1',
                            'title' => '课程列表',
                            'type' => 'menu',
                            'route' => 'admin.course.list',
                        ],
                        [
                            'id' => '1-1-2',
                            'title' => '搜索课程',
                            'type' => 'menu',
                            'route' => 'admin.course.search',
                        ],
                        [
                            'id' => '1-1-3',
                            'title' => '添加课程',
                            'type' => 'menu',
                            'route' => 'admin.course.add',
                        ],
                        [
                            'id' => '1-1-4',
                            'title' => '编辑课程',
                            'type' => 'button',
                            'route' => 'admin.course.edit',
                        ],
                        [
                            'id' => '1-1-5',
                            'title' => '删除课程',
                            'type' => 'button',
                            'route' => 'admin.course.delete',
                        ],
                        [
                            'id' => '1-1-6',
                            'title' => '课程分类',
                            'type' => 'menu',
                            'route' => 'admin.course.category',
                        ],
                    ],
                ],
                [
                    'id' => '1-3',
                    'title' => '套餐管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '1-3-1',
                            'title' => '套餐列表',
                            'type' => 'menu',
                            'route' => 'admin.package.list',
                        ],
                        [
                            'id' => '1-3-2',
                            'title' => '搜索套餐',
                            'type' => 'menu',
                            'route' => 'admin.package.search',
                        ],
                        [
                            'id' => '1-3-3',
                            'title' => '添加套餐',
                            'type' => 'menu',
                            'route' => 'admin.package.add',
                        ],
                        [
                            'id' => '1-3-4',
                            'title' => '编辑套餐',
                            'type' => 'button',
                            'route' => 'admin.package.edit',
                        ],
                        [
                            'id' => '1-3-5',
                            'title' => '删除套餐',
                            'type' => 'button',
                            'route' => 'admin.package.delete',
                        ],
                    ],
                ],
                [
                    'id' => '1-4',
                    'title' => '话题管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '1-4-1',
                            'title' => '话题列表',
                            'type' => 'menu',
                            'route' => 'admin.topic.list',
                        ],
                        [
                            'id' => '1-4-2',
                            'title' => '添加话题',
                            'type' => 'menu',
                            'route' => 'admin.topic.add',
                        ],
                        [
                            'id' => '1-4-3',
                            'title' => '编辑话题',
                            'type' => 'button',
                            'route' => 'admin.topic.edit',
                        ],
                        [
                            'id' => '1-4-4',
                            'title' => '删除话题',
                            'type' => 'button',
                            'route' => 'admin.topic.delete',
                        ],
                    ],
                ],
                [
                    'id' => '1-5',
                    'title' => '单页管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '1-5-1',
                            'title' => '单页列表',
                            'type' => 'menu',
                            'route' => 'admin.page.list',
                        ],
                        [
                            'id' => '1-5-2',
                            'title' => '添加单页',
                            'type' => 'menu',
                            'route' => 'admin.page.add',
                        ],
                        [
                            'id' => '1-5-3',
                            'title' => '编辑单页',
                            'type' => 'button',
                            'route' => 'admin.page.edit',
                        ],
                        [
                            'id' => '1-5-4',
                            'title' => '删除单页',
                            'type' => 'button',
                            'route' => 'admin.page.delete',
                        ],
                    ],
                ],
                [
                    'id' => '1-6',
                    'title' => '帮助管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '1-6-1',
                            'title' => '帮助列表',
                            'type' => 'menu',
                            'route' => 'admin.help.list',
                        ],
                        [
                            'id' => '1-6-2',
                            'title' => '添加帮助',
                            'type' => 'menu',
                            'route' => 'admin.help.add',
                        ],
                        [
                            'id' => '1-6-3',
                            'title' => '编辑帮助',
                            'type' => 'button',
                            'route' => 'admin.help.edit',
                        ],
                        [
                            'id' => '1-6-4',
                            'title' => '删除帮助',
                            'type' => 'button',
                            'route' => 'admin.help.delete',
                        ],
                        [
                            'id' => '1-6-5',
                            'title' => '帮助分类',
                            'type' => 'menu',
                            'route' => 'admin.help.category',
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getOperationNodes()
    {
        return [
            'id' => '2',
            'title' => '运营管理',
            'children' => [
                [
                    'id' => '2-1',
                    'title' => '学员管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '2-1-1',
                            'title' => '学员列表',
                            'type' => 'menu',
                            'route' => 'admin.student.list',
                        ],
                        [
                            'id' => '2-1-2',
                            'title' => '搜索学员',
                            'type' => 'menu',
                            'route' => 'admin.student.search',
                        ],
                        [
                            'id' => '2-1-3',
                            'title' => '添加学员',
                            'type' => 'menu',
                            'route' => 'admin.student.add',
                        ],
                        [
                            'id' => '2-1-4',
                            'title' => '编辑学员',
                            'type' => 'button',
                            'route' => 'admin.student.edit',
                        ],
                    ],
                ],
                [
                    'id' => '2-2',
                    'title' => '咨询管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '2-2-1',
                            'title' => '咨询列表',
                            'type' => 'menu',
                            'route' => 'admin.consult.list',
                        ],
                        [
                            'id' => '2-2-2',
                            'title' => '搜索咨询',
                            'type' => 'menu',
                            'route' => 'admin.consult.search',
                        ],
                        [
                            'id' => '2-2-3',
                            'title' => '编辑咨询',
                            'type' => 'button',
                            'route' => 'admin.consult.edit',
                        ],
                        [
                            'id' => '2-2-4',
                            'title' => '删除咨询',
                            'type' => 'button',
                            'route' => 'admin.consult.delete',
                        ],
                    ],
                ],
                [
                    'id' => '2-3',
                    'title' => '评价管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '2-3-1',
                            'title' => '评价列表',
                            'type' => 'menu',
                            'route' => 'admin.review.list',
                        ],
                        [
                            'id' => '2-3-2',
                            'title' => '搜索评价',
                            'type' => 'menu',
                            'route' => 'admin.review.search',
                        ],
                        [
                            'id' => '2-3-3',
                            'title' => '编辑评价',
                            'type' => 'button',
                            'route' => 'admin.review.edit',
                        ],
                        [
                            'id' => '2-3-4',
                            'title' => '删除评价',
                            'type' => 'button',
                            'route' => 'admin.review.delete',
                        ],
                    ],
                ],
                [
                    'id' => '2-4',
                    'title' => '群组管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '2-4-1',
                            'title' => '群组列表',
                            'type' => 'menu',
                            'route' => 'admin.group.list',
                        ],
                        [
                            'id' => '2-4-2',
                            'title' => '搜索群组',
                            'type' => 'menu',
                            'route' => 'admin.group.search',
                        ],
                        [
                            'id' => '2-4-3',
                            'title' => '添加群组',
                            'type' => 'menu',
                            'route' => 'admin.group.add',
                        ],
                        [
                            'id' => '2-4-4',
                            'title' => '编辑群组',
                            'type' => 'button',
                            'route' => 'admin.group.edit',
                        ],
                        [
                            'id' => '2-4-5',
                            'title' => '删除群组',
                            'type' => 'button',
                            'route' => 'admin.group.delete',
                        ],
                    ],
                ],
                [
                    'id' => '2-5',
                    'title' => '轮播管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '2-5-1',
                            'title' => '轮播列表',
                            'type' => 'menu',
                            'route' => 'admin.slide.list',
                        ],
                        [
                            'id' => '2-5-2',
                            'title' => '添加轮播',
                            'type' => 'menu',
                            'route' => 'admin.slide.add',
                        ],
                        [
                            'id' => '2-5-3',
                            'title' => '编辑轮播',
                            'type' => 'button',
                            'route' => 'admin.slide.edit',
                        ],
                        [
                            'id' => '2-5-4',
                            'title' => '删除轮播',
                            'type' => 'button',
                            'route' => 'admin.slide.delete',
                        ],
                    ],
                ],
                [
                    'id' => '2-6',
                    'title' => '导航管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '2-6-1',
                            'title' => '导航列表',
                            'type' => 'menu',
                            'route' => 'admin.nav.list',
                        ],
                        [
                            'id' => '2-6-2',
                            'title' => '添加导航',
                            'type' => 'menu',
                            'route' => 'admin.nav.add',
                        ],
                        [
                            'id' => '2-6-3',
                            'title' => '编辑导航',
                            'type' => 'button',
                            'route' => 'admin.nav.edit',
                        ],
                        [
                            'id' => '2-6-4',
                            'title' => '删除导航',
                            'type' => 'button',
                            'route' => 'admin.nav.delete',
                        ],
                    ],
                ],
                [
                    'id' => '2-7',
                    'title' => '数据统计',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '2-7-1',
                            'title' => '热卖商品',
                            'type' => 'menu',
                            'route' => 'admin.stat.hot_sales',
                        ],
                        [
                            'id' => '2-7-2',
                            'title' => '成交订单',
                            'type' => 'menu',
                            'route' => 'admin.stat.sales',
                        ],
                        [
                            'id' => '2-7-3',
                            'title' => '售后退款',
                            'type' => 'menu',
                            'route' => 'admin.stat.refunds',
                        ],
                        [
                            'id' => '2-7-4',
                            'title' => '注册用户',
                            'type' => 'menu',
                            'route' => 'admin.stat.reg_users',
                        ],
                        [
                            'id' => '2-7-5',
                            'title' => '活跃用户',
                            'type' => 'menu',
                            'route' => 'admin.stat.online_users',
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getFinanceNodes()
    {
        return [
            'id' => '3',
            'title' => '财务管理',
            'children' => [
                [
                    'id' => '3-1',
                    'title' => '订单管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '3-1-1',
                            'title' => '订单列表',
                            'type' => 'menu',
                            'route' => 'admin.order.list',
                        ],
                        [
                            'id' => '3-1-2',
                            'title' => '搜索订单',
                            'type' => 'menu',
                            'route' => 'admin.order.search',
                        ],
                        [
                            'id' => '3-1-3',
                            'title' => '订单详情',
                            'type' => 'button',
                            'route' => 'admin.order.show',
                        ],
                    ],
                ],
                [
                    'id' => '3-2',
                    'title' => '交易管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '3-2-1',
                            'title' => '交易列表',
                            'type' => 'menu',
                            'route' => 'admin.trade.list',
                        ],
                        [
                            'id' => '3-2-2',
                            'title' => '搜索交易',
                            'type' => 'menu',
                            'route' => 'admin.trade.search',
                        ],
                        [
                            'id' => '3-2-3',
                            'title' => '交易详情',
                            'type' => 'button',
                            'route' => 'admin.trade.show',
                        ],
                        [
                            'id' => '3-2-4',
                            'title' => '交易退款',
                            'type' => 'button',
                            'route' => 'admin.trade.refund',
                        ],
                    ],
                ],
                [
                    'id' => '3-3',
                    'title' => '退款管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '3-3-1',
                            'title' => '退款列表',
                            'type' => 'menu',
                            'route' => 'admin.refund.list',
                        ],
                        [
                            'id' => '3-3-2',
                            'title' => '搜索退款',
                            'type' => 'menu',
                            'route' => 'admin.refund.search',
                        ],
                        [
                            'id' => '3-3-3',
                            'title' => '退款详情',
                            'type' => 'button',
                            'route' => 'admin.refund.show',
                        ],
                        [
                            'id' => '3-3-4',
                            'title' => '审核退款',
                            'type' => 'button',
                            'route' => 'admin.refund.review',
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getUserNodes()
    {
        return [
            'id' => '4',
            'title' => '用户管理',
            'children' => [
                [
                    'id' => '4-1',
                    'title' => '用户管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '4-1-1',
                            'title' => '用户列表',
                            'type' => 'menu',
                            'route' => 'admin.user.list',
                        ],
                        [
                            'id' => '4-1-2',
                            'title' => '搜索用户',
                            'type' => 'menu',
                            'route' => 'admin.user.search',
                        ],
                        [
                            'id' => '4-1-3',
                            'title' => '添加用户',
                            'type' => 'menu',
                            'route' => 'admin.user.add',
                        ],
                        [
                            'id' => '4-1-4',
                            'title' => '编辑用户',
                            'type' => 'button',
                            'route' => 'admin.user.edit',
                        ]
                    ],
                ],
                [
                    'id' => '4-2',
                    'title' => '角色管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '4-2-1',
                            'title' => '角色列表',
                            'type' => 'menu',
                            'route' => 'admin.role.list',
                        ],
                        [
                            'id' => '4-2-2',
                            'title' => '添加角色',
                            'type' => 'menu',
                            'route' => 'admin.role.add',
                        ],
                        [
                            'id' => '4-2-3',
                            'title' => '编辑角色',
                            'type' => 'button',
                            'route' => 'admin.role.edit',
                        ],
                        [
                            'id' => '4-2-4',
                            'title' => '删除角色',
                            'type' => 'button',
                            'route' => 'admin.role.delete',
                        ]
                    ],
                ],
                [
                    'id' => '4-3',
                    'title' => '操作记录',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '4-3-1',
                            'title' => '记录列表',
                            'type' => 'menu',
                            'route' => 'admin.audit.list',
                        ],
                        [
                            'id' => '4-3-2',
                            'title' => '搜索记录',
                            'type' => 'menu',
                            'route' => 'admin.audit.search',
                        ],
                        [
                            'id' => '4-3-3',
                            'title' => '浏览记录',
                            'type' => 'button',
                            'route' => 'admin.audit.show',
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getSettingNodes()
    {
        return [
            'id' => '5',
            'title' => '系统管理',
            'children' => [
                [
                    'id' => '5-1',
                    'title' => '配置管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '5-1-1',
                            'title' => '网站设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.site',
                        ],
                        [
                            'id' => '5-1-2',
                            'title' => '密钥设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.secret',
                        ],
                        [
                            'id' => '5-1-3',
                            'title' => '存储设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.storage',
                        ],
                        [
                            'id' => '5-1-4',
                            'title' => '点播设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.vod',
                        ],
                        [
                            'id' => '5-1-5',
                            'title' => '直播设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.live',
                        ],
                        [
                            'id' => '5-1-6',
                            'title' => '短信设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.sms',
                        ],
                        [
                            'id' => '5-1-7',
                            'title' => '邮件设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.mail',
                        ],
                        [
                            'id' => '5-1-8',
                            'title' => '验证码设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.captcha',
                        ],
                        [
                            'id' => '5-1-9',
                            'title' => '支付设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.pay',
                        ],
                        [
                            'id' => '5-1-10',
                            'title' => '会员设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.vip',
                        ],
                        [
                            'id' => '5-1-11',
                            'title' => '微聊设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.im',
                        ],
                        [
                            'id' => '5-1-12',
                            'title' => '开放登录',
                            'type' => 'menu',
                            'route' => 'admin.setting.oauth',
                        ],
                        [
                            'id' => '5-1-13',
                            'title' => '微信平台',
                            'type' => 'menu',
                            'route' => 'admin.setting.wechat',
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getUtilNodes()
    {
        return [
            'id' => '6',
            'title' => '实用工具',
            'children' => [
                [
                    'id' => '６-1',
                    'title' => '常用工具',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '６-1-1',
                            'title' => '首页缓存',
                            'type' => 'menu',
                            'route' => 'admin.util.index_cache',
                        ],
                    ],
                ],
            ],
        ];
    }

}
