<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

namespace App\Http\Admin\Services;

class AuthNode extends Service
{

    public function getNodes(): array
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

    protected function getContentNodes(): array
    {
        return [
            'id' => '1',
            'title' => '内容管理',
            'children' => [
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
                    ],
                ],
                [
                    'id' => '1-2',
                    'title' => '导航管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '1-2-1',
                            'title' => '导航列表',
                            'type' => 'menu',
                            'route' => 'admin.nav.list',
                        ],
                        [
                            'id' => '1-2-2',
                            'title' => '添加导航',
                            'type' => 'menu',
                            'route' => 'admin.nav.add',
                        ],
                        [
                            'id' => '1-2-3',
                            'title' => '编辑导航',
                            'type' => 'button',
                            'route' => 'admin.nav.edit',
                        ],
                        [
                            'id' => '1-2-4',
                            'title' => '删除导航',
                            'type' => 'button',
                            'route' => 'admin.nav.delete',
                        ],
                    ],
                ],
                [
                    'id' => '1-3',
                    'title' => '单页管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '1-3-1',
                            'title' => '单页列表',
                            'type' => 'menu',
                            'route' => 'admin.page.list',
                        ],
                        [
                            'id' => '1-3-2',
                            'title' => '添加单页',
                            'type' => 'menu',
                            'route' => 'admin.page.add',
                        ],
                        [
                            'id' => '1-3-3',
                            'title' => '编辑单页',
                            'type' => 'button',
                            'route' => 'admin.page.edit',
                        ],
                        [
                            'id' => '1-3-4',
                            'title' => '删除单页',
                            'type' => 'button',
                            'route' => 'admin.page.delete',
                        ],
                    ],
                ],
                [
                    'id' => '1-20',
                    'title' => '分类管理',
                    'type' => 'button',
                    'children' => [
                        [
                            'id' => '1-20-1',
                            'title' => '分类列表',
                            'type' => 'button',
                            'route' => 'admin.category.list',
                            'params' => ['type' => 1],
                        ],
                        [
                            'id' => '1-20-2',
                            'title' => '添加分类',
                            'type' => 'button',
                            'route' => 'admin.category.add',
                            'params' => ['type' => 1],
                        ],
                        [
                            'id' => '1-20-3',
                            'title' => '编辑分类',
                            'type' => 'button',
                            'route' => 'admin.category.edit',
                        ],
                        [
                            'id' => '1-20-4',
                            'title' => '删除分类',
                            'type' => 'button',
                            'route' => 'admin.category.delete',
                        ],
                    ],
                ],
                [
                    'id' => '1-21',
                    'title' => '课程管理',
                    'type' => 'button',
                    'children' => [
                        [
                            'id' => '1-21-1',
                            'title' => '课程列表',
                            'type' => 'button',
                            'route' => 'admin.course.list',
                        ],
                        [
                            'id' => '1-21-2',
                            'title' => '搜索课程',
                            'type' => 'button',
                            'route' => 'admin.course.search',
                        ],
                        [
                            'id' => '1-21-3',
                            'title' => '添加课程',
                            'type' => 'button',
                            'route' => 'admin.course.add',
                        ],
                        [
                            'id' => '1-21-4',
                            'title' => '编辑课程',
                            'type' => 'button',
                            'route' => 'admin.course.edit',
                        ],
                        [
                            'id' => '1-21-5',
                            'title' => '删除课程',
                            'type' => 'button',
                            'route' => 'admin.course.delete',
                        ],
                        [
                            'id' => '1-21-6',
                            'title' => '课程分类',
                            'type' => 'button',
                            'route' => 'admin.course.category',
                        ],
                        [
                            'id' => '1-21-8',
                            'title' => '课程学员',
                            'type' => 'button',
                            'route' => 'admin.course.users',
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getOperationNodes(): array
    {
        return [
            'id' => '2',
            'title' => '运营管理',
            'children' => [
                [
                    'id' => '2-2',
                    'title' => '营销中心',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '2-2-1',
                            'title' => '会员套餐',
                            'type' => 'menu',
                            'route' => 'admin.vip.list',
                        ],
                    ],
                ],
                [
                    'id' => '2-5',
                    'title' => '评价管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '2-5-1',
                            'title' => '评价列表',
                            'type' => 'menu',
                            'route' => 'admin.review.list',
                        ],
                        [
                            'id' => '2-5-2',
                            'title' => '搜索评价',
                            'type' => 'menu',
                            'route' => 'admin.review.search',
                        ],
                        [
                            'id' => '2-5-3',
                            'title' => '添加评价',
                            'type' => 'menu',
                            'route' => 'admin.review.add',
                        ],
                        [
                            'id' => '2-5-4',
                            'title' => '编辑评价',
                            'type' => 'button',
                            'route' => 'admin.review.edit',
                        ],
                        [
                            'id' => '2-5-5',
                            'title' => '删除评价',
                            'type' => 'button',
                            'route' => 'admin.review.delete',
                        ],
                        [
                            'id' => '2-5-6',
                            'title' => '审核评价',
                            'type' => 'button',
                            'route' => 'admin.review.moderate',
                        ],
                    ],
                ],
                [
                    'id' => '2-6',
                    'title' => '轮播管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '2-6-1',
                            'title' => '轮播列表',
                            'type' => 'menu',
                            'route' => 'admin.slide.list',
                        ],
                        [
                            'id' => '2-6-2',
                            'title' => '搜索轮播',
                            'type' => 'menu',
                            'route' => 'admin.slide.search',
                        ],
                        [
                            'id' => '2-6-3',
                            'title' => '添加轮播',
                            'type' => 'menu',
                            'route' => 'admin.slide.add',
                        ],
                        [
                            'id' => '2-6-4',
                            'title' => '编辑轮播',
                            'type' => 'button',
                            'route' => 'admin.slide.edit',
                        ],
                        [
                            'id' => '2-6-5',
                            'title' => '删除轮播',
                            'type' => 'button',
                            'route' => 'admin.slide.delete',
                        ],
                    ],
                ],
                [
                    'id' => '2-7',
                    'title' => '审核队列',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '2-7-1',
                            'title' => '评价审核',
                            'type' => 'menu',
                            'route' => 'admin.mod.reviews',
                        ],
                    ],
                ],
                [
                    'id' => '2-26',
                    'title' => '会员套餐',
                    'type' => 'button',
                    'children' => [
                        [
                            'id' => '2-26-1',
                            'title' => '套餐列表',
                            'type' => 'button',
                            'route' => 'admin.vip.list',
                        ],
                        [
                            'id' => '2-26-2',
                            'title' => '添加套餐',
                            'type' => 'button',
                            'route' => 'admin.vip.add',
                        ],
                        [
                            'id' => '2-26-3',
                            'title' => '编辑套餐',
                            'type' => 'button',
                            'route' => 'admin.vip.edit',
                        ],
                        [
                            'id' => '2-26-4',
                            'title' => '删除套餐',
                            'type' => 'button',
                            'route' => 'admin.vip.delete',
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getFinanceNodes(): array
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
                            'id' => '3-1-4',
                            'title' => '导出订单',
                            'type' => 'button',
                            'route' => 'admin.order.export',
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

    protected function getUserNodes(): array
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
                        ],
                        [
                            'id' => '4-1-5',
                            'title' => '用户详情',
                            'type' => 'button',
                            'route' => 'admin.user.show',
                        ],
                        [
                            'id' => '4-1-20',
                            'title' => '赠送课程',
                            'type' => 'button',
                            'route' => 'admin.user.assign_course',
                        ],
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
            ],
        ];
    }

    protected function getSettingNodes(): array
    {
        return [
            'id' => '5',
            'title' => '系统管理',
            'children' => [
                [
                    'id' => '5-1',
                    'title' => '基本配置',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '5-1-1',
                            'title' => '网站信息',
                            'type' => 'menu',
                            'route' => 'admin.setting.site',
                        ],
                        [
                            'id' => '5-1-2',
                            'title' => '联系方式',
                            'type' => 'menu',
                            'route' => 'admin.setting.contact',
                        ],
                        [
                            'id' => '5-1-3',
                            'title' => '邮件设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.mail',
                        ],
                        [
                            'id' => '5-1-4',
                            'title' => '支付设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.pay',
                        ],
                        [
                            'id' => '5-1-7',
                            'title' => '系统安全',
                            'type' => 'menu',
                            'route' => 'admin.setting.security',
                        ],
                    ],
                ],
                [
                    'id' => '5-2',
                    'title' => '腾讯云配置',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '5-2-1',
                            'title' => '密钥设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.secret',
                        ],
                        [
                            'id' => '5-2-2',
                            'title' => '短信设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.sms',
                        ],
                        [
                            'id' => '5-2-4',
                            'title' => '存储设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.storage',
                        ],
                        [
                            'id' => '5-2-5',
                            'title' => '点播设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.vod',
                        ],
                    ],
                ],
                [
                    'id' => '5-4',
                    'title' => '操作记录',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '5-4-1',
                            'title' => '记录列表',
                            'type' => 'menu',
                            'route' => 'admin.audit.list',
                        ],
                        [
                            'id' => '5-4-2',
                            'title' => '搜索记录',
                            'type' => 'menu',
                            'route' => 'admin.audit.search',
                        ],
                        [
                            'id' => '5-4-3',
                            'title' => '浏览记录',
                            'type' => 'button',
                            'route' => 'admin.audit.show',
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getUtilNodes(): array
    {
        return [
            'id' => '6',
            'title' => '实用工具',
            'children' => [
                [
                    'id' => '6-1',
                    'title' => '常用工具',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '6-1-1',
                            'title' => '刷新缓存',
                            'type' => 'menu',
                            'route' => 'admin.util.cache',
                        ],
                    ],
                ],
            ],
        ];
    }

}
