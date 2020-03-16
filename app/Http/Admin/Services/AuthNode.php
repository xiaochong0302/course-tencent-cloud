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
        $nodes[] = $this->getConfigNodes();

        return $nodes;
    }

    protected function getContentNodes()
    {
        $nodes = [
            'id' => '1',
            'label' => '内容管理',
            'child' => [
                [
                    'id' => '1-1',
                    'label' => '课程管理',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '1-1-1',
                            'label' => '课程列表',
                            'type' => 'menu',
                            'route' => 'admin.course.list',
                        ],
                        [
                            'id' => '1-1-2',
                            'label' => '搜索课程',
                            'type' => 'menu',
                            'route' => 'admin.course.search',
                        ],
                        [
                            'id' => '1-1-3',
                            'label' => '添加课程',
                            'type' => 'menu',
                            'route' => 'admin.course.add',
                        ],
                        [
                            'id' => '1-1-4',
                            'label' => '编辑课程',
                            'type' => 'button',
                            'route' => 'admin.course.edit',
                        ],
                        [
                            'id' => '1-1-5',
                            'label' => '删除课程',
                            'type' => 'button',
                            'route' => 'admin.course.edit',
                        ],
                    ],
                ],
                [
                    'id' => '1-2',
                    'label' => '分类管理',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '1-2-1',
                            'label' => '分类列表',
                            'type' => 'menu',
                            'route' => 'admin.category.list',
                        ],
                        [
                            'id' => '1-2-2',
                            'label' => '添加分类',
                            'type' => 'menu',
                            'route' => 'admin.category.add',
                        ],
                        [
                            'id' => '1-2-3',
                            'label' => '编辑分类',
                            'type' => 'button',
                            'route' => 'admin.category.edit',
                        ],
                        [
                            'id' => '1-2-4',
                            'label' => '删除分类',
                            'type' => 'button',
                            'route' => 'admin.category.delete',
                        ],
                    ],
                ],
                [
                    'id' => '1-3',
                    'label' => '套餐管理',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '1-3-1',
                            'label' => '套餐列表',
                            'type' => 'menu',
                            'route' => 'admin.package.list',
                        ],
                        [
                            'id' => '1-3-2',
                            'label' => '添加套餐',
                            'type' => 'menu',
                            'route' => 'admin.package.add',
                        ],
                        [
                            'id' => '1-3-3',
                            'label' => '编辑套餐',
                            'type' => 'button',
                            'route' => 'admin.package.edit',
                        ],
                        [
                            'id' => '1-3-4',
                            'label' => '删除套餐',
                            'type' => 'button',
                            'route' => 'admin.package.delete',
                        ],
                    ],
                ],
                [
                    'id' => '1-4',
                    'label' => '话题管理',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '1-4-1',
                            'label' => '话题列表',
                            'type' => 'menu',
                            'route' => 'admin.topic.list',
                        ],
                        [
                            'id' => '1-4-2',
                            'label' => '添加话题',
                            'type' => 'menu',
                            'route' => 'admin.topic.add',
                        ],
                        [
                            'id' => '1-4-3',
                            'label' => '编辑话题',
                            'type' => 'button',
                            'route' => 'admin.topic.edit',
                        ],
                        [
                            'id' => '1-4-4',
                            'label' => '删除话题',
                            'type' => 'button',
                            'route' => 'admin.topic.delete',
                        ],
                    ],
                ],
                [
                    'id' => '1-5',
                    'label' => '单页管理',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '1-5-1',
                            'label' => '单页列表',
                            'type' => 'menu',
                            'route' => 'admin.page.list',
                        ],
                        [
                            'id' => '1-5-2',
                            'label' => '添加单页',
                            'type' => 'menu',
                            'route' => 'admin.page.add',
                        ],
                        [
                            'id' => '1-5-3',
                            'label' => '编辑单页',
                            'type' => 'button',
                            'route' => 'admin.page.edit',
                        ],
                        [
                            'id' => '1-5-4',
                            'label' => '删除单页',
                            'type' => 'button',
                            'route' => 'admin.page.delete',
                        ],
                    ],
                ],
                [
                    'id' => '1-6',
                    'label' => '帮助管理',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '1-6-1',
                            'label' => '帮助列表',
                            'type' => 'menu',
                            'route' => 'admin.help.list',
                        ],
                        [
                            'id' => '1-6-2',
                            'label' => '添加帮助',
                            'type' => 'menu',
                            'route' => 'admin.help.add',
                        ],
                        [
                            'id' => '1-6-3',
                            'label' => '编辑帮助',
                            'type' => 'button',
                            'route' => 'admin.help.edit',
                        ],
                        [
                            'id' => '1-6-4',
                            'label' => '删除帮助',
                            'type' => 'button',
                            'route' => 'admin.help.delete',
                        ],
                    ],
                ],
            ],
        ];

        return $nodes;
    }

    protected function getOperationNodes()
    {
        $nodes = [
            'id' => '2',
            'label' => '运营管理',
            'child' => [
                [
                    'id' => '2-1',
                    'label' => '学员管理',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '2-1-1',
                            'label' => '学员列表',
                            'type' => 'menu',
                            'route' => 'admin.student.list',
                        ],
                        [
                            'id' => '2-1-2',
                            'label' => '搜索学员',
                            'type' => 'menu',
                            'route' => 'admin.student.search',
                        ],
                        [
                            'id' => '2-1-3',
                            'label' => '添加学员',
                            'type' => 'menu',
                            'route' => 'admin.student.add',
                        ],
                        [
                            'id' => '2-1-4',
                            'label' => '编辑学员',
                            'type' => 'button',
                            'route' => 'admin.student.edit',
                        ],
                    ],
                ],
                [
                    'id' => '2-2',
                    'label' => '咨询管理',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '2-2-1',
                            'label' => '咨询列表',
                            'type' => 'menu',
                            'route' => 'admin.consult.list',
                        ],
                        [
                            'id' => '2-2-2',
                            'label' => '搜索咨询',
                            'type' => 'menu',
                            'route' => 'admin.consult.search',
                        ],
                        [
                            'id' => '2-2-3',
                            'label' => '编辑咨询',
                            'type' => 'button',
                            'route' => 'admin.consult.edit',
                        ],
                        [
                            'id' => '2-2-4',
                            'label' => '删除咨询',
                            'type' => 'button',
                            'route' => 'admin.consult.delete',
                        ],
                    ],
                ],
                [
                    'id' => '2-3',
                    'label' => '评价管理',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '2-3-1',
                            'label' => '评价列表',
                            'type' => 'menu',
                            'route' => 'admin.review.list',
                        ],
                        [
                            'id' => '2-3-2',
                            'label' => '搜索评价',
                            'type' => 'menu',
                            'route' => 'admin.review.search',
                        ],
                        [
                            'id' => '2-3-3',
                            'label' => '编辑评价',
                            'type' => 'button',
                            'route' => 'admin.review.edit',
                        ],
                        [
                            'id' => '2-3-4',
                            'label' => '删除评价',
                            'type' => 'button',
                            'route' => 'admin.review.delete',
                        ],
                    ],
                ],
                [
                    'id' => '2-4',
                    'label' => '评论管理',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '2-4-1',
                            'label' => '评论列表',
                            'type' => 'menu',
                            'route' => 'admin.comment.list',
                        ],
                        [
                            'id' => '2-4-2',
                            'label' => '搜索评论',
                            'type' => 'menu',
                            'route' => 'admin.comment.search',
                        ],
                        [
                            'id' => '2-4-3',
                            'label' => '编辑评论',
                            'type' => 'button',
                            'route' => 'admin.comment.edit',
                        ],
                        [
                            'id' => '2-4-4',
                            'label' => '删除评论',
                            'type' => 'button',
                            'route' => 'admin.comment.delete',
                        ],
                    ],
                ],
                [
                    'id' => '2-5',
                    'label' => '轮播管理',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '2-5-1',
                            'label' => '轮播列表',
                            'type' => 'menu',
                            'route' => 'admin.slide.list',
                        ],
                        [
                            'id' => '2-5-2',
                            'label' => '添加轮播',
                            'type' => 'menu',
                            'route' => 'admin.slide.add',
                        ],
                        [
                            'id' => '2-5-3',
                            'label' => '编辑轮播',
                            'type' => 'button',
                            'route' => 'admin.slide.edit',
                        ],
                        [
                            'id' => '2-5-4',
                            'label' => '删除轮播',
                            'type' => 'button',
                            'route' => 'admin.slide.delete',
                        ],
                    ],
                ],
                [
                    'id' => '2-6',
                    'label' => '导航管理',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '2-6-1',
                            'label' => '导航列表',
                            'type' => 'menu',
                            'route' => 'admin.nav.list',
                        ],
                        [
                            'id' => '2-6-2',
                            'label' => '添加导航',
                            'type' => 'menu',
                            'route' => 'admin.nav.add',
                        ],
                        [
                            'id' => '2-6-3',
                            'label' => '编辑导航',
                            'type' => 'button',
                            'route' => 'admin.nav.edit',
                        ],
                        [
                            'id' => '2-6-4',
                            'label' => '删除导航',
                            'type' => 'button',
                            'route' => 'admin.nav.delete',
                        ],
                    ],
                ],
            ],
        ];

        return $nodes;
    }

    protected function getFinanceNodes()
    {
        $nodes = [
            'id' => '3',
            'label' => '财务管理',
            'child' => [
                [
                    'id' => '3-1',
                    'label' => '订单管理',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '3-1-1',
                            'label' => '订单列表',
                            'type' => 'menu',
                            'route' => 'admin.order.list',
                        ],
                        [
                            'id' => '3-1-2',
                            'label' => '搜索订单',
                            'type' => 'menu',
                            'route' => 'admin.order.search',
                        ],
                        [
                            'id' => '3-1-3',
                            'label' => '订单详情',
                            'type' => 'button',
                            'route' => 'admin.order.show',
                        ],
                        [
                            'id' => '3-1-4',
                            'label' => '关闭订单',
                            'type' => 'button',
                            'route' => 'admin.order.close',
                        ],
                    ],
                ],
                [
                    'id' => '3-2',
                    'label' => '交易管理',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '3-2-1',
                            'label' => '交易记录',
                            'type' => 'menu',
                            'route' => 'admin.trade.list',
                        ],
                        [
                            'id' => '3-2-2',
                            'label' => '搜索交易',
                            'type' => 'menu',
                            'route' => 'admin.trade.search',
                        ],
                        [
                            'id' => '3-2-3',
                            'label' => '关闭交易',
                            'type' => 'button',
                            'route' => 'admin.trade.close',
                        ],
                        [
                            'id' => '3-2-4',
                            'label' => '交易退款',
                            'type' => 'button',
                            'route' => 'admin.trade.refund',
                        ],
                    ],
                ],
                [
                    'id' => '3-3',
                    'label' => '退款管理',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '3-3-1',
                            'label' => '退款列表',
                            'type' => 'menu',
                            'route' => 'admin.refund.list',
                        ],
                        [
                            'id' => '3-3-2',
                            'label' => '搜索退款',
                            'type' => 'menu',
                            'route' => 'admin.refund.search',
                        ],
                        [
                            'id' => '3-3-3',
                            'label' => '退款详情',
                            'type' => 'button',
                            'route' => 'admin.refund.show',
                        ],
                        [
                            'id' => '3-3-4',
                            'label' => '审核退款',
                            'type' => 'button',
                            'route' => 'admin.refund.review',
                        ],
                    ],
                ],
            ],
        ];

        return $nodes;
    }

    protected function getUserNodes()
    {
        $nodes = [
            'id' => '4',
            'label' => '用户管理',
            'child' => [
                [
                    'id' => '4-1',
                    'label' => '用户管理',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '4-1-1',
                            'label' => '用户列表',
                            'type' => 'menu',
                            'route' => 'admin.user.list',
                        ],
                        [
                            'id' => '4-1-2',
                            'label' => '搜索用户',
                            'type' => 'menu',
                            'route' => 'admin.user.search',
                        ],
                        [
                            'id' => '4-1-3',
                            'label' => '添加用户',
                            'type' => 'menu',
                            'route' => 'admin.user.add',
                        ],
                        [
                            'id' => '4-1-4',
                            'label' => '编辑用户',
                            'type' => 'button',
                            'route' => 'admin.user.edit',
                        ]
                    ],
                ],
                [
                    'id' => '4-2',
                    'label' => '角色管理',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '4-2-1',
                            'label' => '角色列表',
                            'type' => 'menu',
                            'route' => 'admin.role.list',
                        ],
                        [
                            'id' => '4-2-2',
                            'label' => '添加角色',
                            'type' => 'menu',
                            'route' => 'admin.role.add',
                        ],
                        [
                            'id' => '4-2-3',
                            'label' => '编辑角色',
                            'type' => 'button',
                            'route' => 'admin.role.edit',
                        ],
                        [
                            'id' => '4-2-4',
                            'label' => '删除角色',
                            'type' => 'button',
                            'route' => 'admin.role.delete',
                        ]
                    ],
                ],
                [
                    'id' => '4-3',
                    'label' => '操作记录',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '4-3-1',
                            'label' => '记录列表',
                            'type' => 'menu',
                            'route' => 'admin.audit.list',
                        ],
                        [
                            'id' => '4-3-2',
                            'label' => '搜索记录',
                            'type' => 'menu',
                            'route' => 'admin.audit.search',
                        ],
                        [
                            'id' => '4-3-3',
                            'label' => '浏览记录',
                            'type' => 'button',
                            'route' => 'admin.audit.show',
                        ],
                    ],
                ],
            ],
        ];

        return $nodes;
    }

    protected function getConfigNodes()
    {
        $nodes = [
            'id' => '5',
            'label' => '系统配置',
            'child' => [
                [
                    'id' => '5-1',
                    'label' => '配置管理',
                    'type' => 'menu',
                    'child' => [
                        [
                            'id' => '5-1-1',
                            'label' => '网站设置',
                            'type' => 'menu',
                            'route' => 'admin.config.site',
                        ],
                        [
                            'id' => '5-1-2',
                            'label' => '密钥设置',
                            'type' => 'menu',
                            'route' => 'admin.config.secret',
                        ],
                        [
                            'id' => '5-1-3',
                            'label' => '存储设置',
                            'type' => 'menu',
                            'route' => 'admin.config.storage',
                        ],
                        [
                            'id' => '5-1-4',
                            'label' => '点播设置',
                            'type' => 'menu',
                            'route' => 'admin.config.vod',
                        ],
                        [
                            'id' => '5-1-5',
                            'label' => '直播设置',
                            'type' => 'menu',
                            'route' => 'admin.config.live',
                        ],
                        [
                            'id' => '5-1-6',
                            'label' => '短信设置',
                            'type' => 'menu',
                            'route' => 'admin.config.smser',
                        ],
                        [
                            'id' => '5-1-7',
                            'label' => '邮件设置',
                            'type' => 'menu',
                            'route' => 'admin.config.mailer',
                        ],
                        [
                            'id' => '5-1-8',
                            'label' => '验证码设置',
                            'type' => 'menu',
                            'route' => 'admin.config.captcha',
                        ],
                        [
                            'id' => '5-1-9',
                            'label' => '支付设置',
                            'type' => 'menu',
                            'route' => 'admin.config.payment',
                        ],
                        [
                            'id' => '5-1-10',
                            'label' => '会员设置',
                            'type' => 'menu',
                            'route' => 'admin.config.vip',
                        ]
                    ],
                ],
            ],
        ];

        return $nodes;
    }

}
