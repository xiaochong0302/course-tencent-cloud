<?php
/**
 * @copyright Copyright (c) 2021 深圳市酷瓜软件有限公司
 * @license https://opensource.org/licenses/GPL-2.0
 * @link https://www.koogua.com
 */

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
                            'title' => '套餐列表',
                            'type' => 'menu',
                            'route' => 'admin.package.list',
                        ],
                        [
                            'id' => '1-1-3',
                            'title' => '专题列表',
                            'type' => 'menu',
                            'route' => 'admin.topic.list',
                        ],
                    ],
                ],
                [
                    'id' => '1-6',
                    'title' => '文章管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '1-6-1',
                            'title' => '文章列表',
                            'type' => 'menu',
                            'route' => 'admin.article.list',
                        ],
                        [
                            'id' => '1-6-2',
                            'title' => '搜索文章',
                            'type' => 'menu',
                            'route' => 'admin.article.search',
                        ],
                        [
                            'id' => '1-6-3',
                            'title' => '添加文章',
                            'type' => 'menu',
                            'route' => 'admin.article.add',
                        ],
                        [
                            'id' => '1-6-4',
                            'title' => '编辑文章',
                            'type' => 'button',
                            'route' => 'admin.article.edit',
                        ],
                        [
                            'id' => '1-6-5',
                            'title' => '删除文章',
                            'type' => 'button',
                            'route' => 'admin.article.delete',
                        ],
                        [
                            'id' => '1-6-6',
                            'title' => '文章详情',
                            'type' => 'button',
                            'route' => 'admin.article.show',
                        ],
                        [
                            'id' => '1-6-7',
                            'title' => '审核文章',
                            'type' => 'button',
                            'route' => 'admin.article.moderate',
                        ],
                        [
                            'id' => '1-6-8',
                            'title' => '审核举报',
                            'type' => 'button',
                            'route' => 'admin.article.report',
                        ],
                    ],
                ],
                [
                    'id' => '1-7',
                    'title' => '问答管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '1-7-1',
                            'title' => '问题列表',
                            'type' => 'menu',
                            'route' => 'admin.question.list',
                        ],
                        [
                            'id' => '1-7-2',
                            'title' => '答案列表',
                            'type' => 'menu',
                            'route' => 'admin.answer.list',
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
                    'id' => '1-4',
                    'title' => '帮助管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '1-4-1',
                            'title' => '帮助列表',
                            'type' => 'menu',
                            'route' => 'admin.help.list',
                        ],
                        [
                            'id' => '1-4-2',
                            'title' => '添加帮助',
                            'type' => 'menu',
                            'route' => 'admin.help.add',
                        ],
                        [
                            'id' => '1-4-3',
                            'title' => '编辑帮助',
                            'type' => 'button',
                            'route' => 'admin.help.edit',
                        ],
                        [
                            'id' => '1-4-4',
                            'title' => '删除帮助',
                            'type' => 'button',
                            'route' => 'admin.help.delete',
                        ],
                        [
                            'id' => '1-4-5',
                            'title' => '帮助分类',
                            'type' => 'menu',
                            'route' => 'admin.help.category',
                        ],
                    ],
                ],
                [
                    'id' => '1-5',
                    'title' => '标签管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '1-5-1',
                            'title' => '标签列表',
                            'type' => 'menu',
                            'route' => 'admin.tag.list',
                        ],
                        [
                            'id' => '1-5-2',
                            'title' => '搜索标签',
                            'type' => 'menu',
                            'route' => 'admin.tag.search',
                        ],
                        [
                            'id' => '1-5-3',
                            'title' => '添加标签',
                            'type' => 'menu',
                            'route' => 'admin.tag.add',
                        ],
                        [
                            'id' => '1-5-4',
                            'title' => '编辑标签',
                            'type' => 'button',
                            'route' => 'admin.tag.edit',
                        ],
                        [
                            'id' => '1-5-5',
                            'title' => '删除标签',
                            'type' => 'button',
                            'route' => 'admin.tag.delete',
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
                    ],
                ],
                [
                    'id' => '1-22',
                    'title' => '套餐管理',
                    'type' => 'button',
                    'children' => [
                        [
                            'id' => '1-22-1',
                            'title' => '套餐列表',
                            'type' => 'button',
                            'route' => 'admin.package.list',
                        ],
                        [
                            'id' => '1-22-2',
                            'title' => '搜索套餐',
                            'type' => 'button',
                            'route' => 'admin.package.search',
                        ],
                        [
                            'id' => '1-22-3',
                            'title' => '添加套餐',
                            'type' => 'button',
                            'route' => 'admin.package.add',
                        ],
                        [
                            'id' => '1-22-4',
                            'title' => '编辑套餐',
                            'type' => 'button',
                            'route' => 'admin.package.edit',
                        ],
                        [
                            'id' => '1-22-5',
                            'title' => '删除套餐',
                            'type' => 'button',
                            'route' => 'admin.package.delete',
                        ],
                    ],
                ],
                [
                    'id' => '1-23',
                    'title' => '专题管理',
                    'type' => 'button',
                    'children' => [
                        [
                            'id' => '1-23-1',
                            'title' => '专题列表',
                            'type' => 'button',
                            'route' => 'admin.topic.list',
                        ],
                        [
                            'id' => '1-23-2',
                            'title' => '搜索专题',
                            'type' => 'button',
                            'route' => 'admin.topic.search',
                        ],
                        [
                            'id' => '1-23-3',
                            'title' => '添加专题',
                            'type' => 'button',
                            'route' => 'admin.topic.add',
                        ],
                        [
                            'id' => '1-23-4',
                            'title' => '编辑专题',
                            'type' => 'button',
                            'route' => 'admin.topic.edit',
                        ],
                        [
                            'id' => '1-23-5',
                            'title' => '删除专题',
                            'type' => 'button',
                            'route' => 'admin.topic.delete',
                        ],
                    ],
                ],
                [
                    'id' => '1-24',
                    'title' => '问题管理',
                    'type' => 'button',
                    'children' => [
                        [
                            'id' => '1-24-1',
                            'title' => '问题列表',
                            'type' => 'button',
                            'route' => 'admin.question.list',
                        ],
                        [
                            'id' => '1-24-2',
                            'title' => '搜索问题',
                            'type' => 'button',
                            'route' => 'admin.question.search',
                        ],
                        [
                            'id' => '1-24-3',
                            'title' => '添加问题',
                            'type' => 'button',
                            'route' => 'admin.question.add',
                        ],
                        [
                            'id' => '1-24-4',
                            'title' => '编辑问题',
                            'type' => 'button',
                            'route' => 'admin.question.edit',
                        ],
                        [
                            'id' => '1-24-5',
                            'title' => '删除问题',
                            'type' => 'button',
                            'route' => 'admin.question.delete',
                        ],
                        [
                            'id' => '1-24-6',
                            'title' => '问题详情',
                            'type' => 'button',
                            'route' => 'admin.question.show',
                        ],
                        [
                            'id' => '1-24-7',
                            'title' => '审核问题',
                            'type' => 'button',
                            'route' => 'admin.question.moderate',
                        ],
                        [
                            'id' => '1-24-8',
                            'title' => '审核举报',
                            'type' => 'button',
                            'route' => 'admin.question.report',
                        ],
                    ],
                ],
                [
                    'id' => '1-25',
                    'title' => '回答管理',
                    'type' => 'button',
                    'children' => [
                        [
                            'id' => '1-25-1',
                            'title' => '回答列表',
                            'type' => 'button',
                            'route' => 'admin.answer.list',
                        ],
                        [
                            'id' => '1-25-2',
                            'title' => '搜索回答',
                            'type' => 'button',
                            'route' => 'admin.answer.search',
                        ],
                        [
                            'id' => '1-25-3',
                            'title' => '添加答案',
                            'type' => 'button',
                            'route' => 'admin.answer.add',
                        ],
                        [
                            'id' => '1-25-4',
                            'title' => '编辑回答',
                            'type' => 'button',
                            'route' => 'admin.answer.edit',
                        ],
                        [
                            'id' => '1-25-5',
                            'title' => '删除回答',
                            'type' => 'button',
                            'route' => 'admin.answer.delete',
                        ],
                        [
                            'id' => '1-25-6',
                            'title' => '回答详情',
                            'type' => 'button',
                            'route' => 'admin.answer.show',
                        ],
                        [
                            'id' => '1-25-7',
                            'title' => '审核回答',
                            'type' => 'button',
                            'route' => 'admin.answer.moderate',
                        ],
                        [
                            'id' => '1-25-8',
                            'title' => '审核举报',
                            'type' => 'button',
                            'route' => 'admin.answer.report',
                        ],
                    ],
                ],
                [
                    'id' => '1-26',
                    'title' => '评论管理',
                    'type' => 'button',
                    'children' => [
                        [
                            'id' => '1-26-1',
                            'title' => '评论列表',
                            'type' => 'button',
                            'route' => 'admin.comment.list',
                        ],
                        [
                            'id' => '1-26-2',
                            'title' => '搜索评论',
                            'type' => 'button',
                            'route' => 'admin.comment.search',
                        ],
                        [
                            'id' => '1-26-3',
                            'title' => '编辑评论',
                            'type' => 'button',
                            'route' => 'admin.comment.edit',
                        ],
                        [
                            'id' => '1-26-4',
                            'title' => '删除评论',
                            'type' => 'button',
                            'route' => 'admin.comment.delete',
                        ],
                        [
                            'id' => '1-26-5',
                            'title' => '评论审核',
                            'type' => 'button',
                            'route' => 'admin.comment.moderate',
                        ],
                        [
                            'id' => '1-26-6',
                            'title' => '举报审核',
                            'type' => 'button',
                            'route' => 'admin.comment.report',
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
                    'title' => '数据统计',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '2-1-1',
                            'title' => '热卖商品',
                            'type' => 'menu',
                            'route' => 'admin.stat.hot_sales',
                        ],
                        [
                            'id' => '2-1-2',
                            'title' => '成交订单',
                            'type' => 'menu',
                            'route' => 'admin.stat.sales',
                        ],
                        [
                            'id' => '2-1-3',
                            'title' => '售后退款',
                            'type' => 'menu',
                            'route' => 'admin.stat.refunds',
                        ],
                        [
                            'id' => '2-1-4',
                            'title' => '注册用户',
                            'type' => 'menu',
                            'route' => 'admin.stat.reg_users',
                        ],
                        [
                            'id' => '2-1-5',
                            'title' => '活跃用户',
                            'type' => 'menu',
                            'route' => 'admin.stat.online_users',
                        ],
                    ],
                ],
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
                        [
                            'id' => '2-2-2',
                            'title' => '积分商城',
                            'type' => 'menu',
                            'route' => 'admin.point_gift.list',
                        ],
                    ],
                ],
                [
                    'id' => '2-4',
                    'title' => '咨询管理',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '2-4-1',
                            'title' => '咨询列表',
                            'type' => 'menu',
                            'route' => 'admin.consult.list',
                        ],
                        [
                            'id' => '2-4-2',
                            'title' => '搜索咨询',
                            'type' => 'menu',
                            'route' => 'admin.consult.search',
                        ],
                        [
                            'id' => '2-4-3',
                            'title' => '编辑咨询',
                            'type' => 'button',
                            'route' => 'admin.consult.edit',
                        ],
                        [
                            'id' => '2-4-4',
                            'title' => '删除咨询',
                            'type' => 'button',
                            'route' => 'admin.consult.delete',
                        ],
                        [
                            'id' => '2-4-5',
                            'title' => '审核咨询',
                            'type' => 'button',
                            'route' => 'admin.consult.moderate',
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
                            'title' => '编辑评价',
                            'type' => 'button',
                            'route' => 'admin.review.edit',
                        ],
                        [
                            'id' => '2-5-4',
                            'title' => '删除评价',
                            'type' => 'button',
                            'route' => 'admin.review.delete',
                        ],
                        [
                            'id' => '2-5-5',
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
                        [
                            'id' => '2-7-2',
                            'title' => '咨询审核',
                            'type' => 'menu',
                            'route' => 'admin.mod.consults',
                        ],
                        [
                            'id' => '2-7-3',
                            'title' => '文章审核',
                            'type' => 'menu',
                            'route' => 'admin.mod.articles',
                        ],
                        [
                            'id' => '2-7-4',
                            'title' => '问题审核',
                            'type' => 'menu',
                            'route' => 'admin.mod.questions',
                        ],
                        [
                            'id' => '2-7-5',
                            'title' => '回答审核',
                            'type' => 'menu',
                            'route' => 'admin.mod.answers',
                        ],
                        [
                            'id' => '2-7-6',
                            'title' => '评论审核',
                            'type' => 'menu',
                            'route' => 'admin.mod.comments',
                        ],
                    ],
                ],
                [
                    'id' => '2-8',
                    'title' => '举报队列',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '2-8-1',
                            'title' => '文章举报',
                            'type' => 'menu',
                            'route' => 'admin.report.articles',
                        ],
                        [
                            'id' => '2-8-2',
                            'title' => '问题举报',
                            'type' => 'menu',
                            'route' => 'admin.report.questions',
                        ],
                        [
                            'id' => '2-8-3',
                            'title' => '回答举报',
                            'type' => 'menu',
                            'route' => 'admin.report.answers',
                        ],
                        [
                            'id' => '2-8-4',
                            'title' => '评论举报',
                            'type' => 'menu',
                            'route' => 'admin.report.comments',
                        ],
                    ],
                ],
                [
                    'id' => '2-20',
                    'title' => '积分商城',
                    'type' => 'button',
                    'children' => [
                        [
                            'id' => '2-20-1',
                            'title' => '礼品列表',
                            'type' => 'button',
                            'route' => 'admin.point_gift.list',
                        ],
                        [
                            'id' => '2-20-2',
                            'title' => '兑换记录',
                            'type' => 'button',
                            'route' => 'admin.point_gift_redeem.list',
                        ],
                        [
                            'id' => '2-20-3',
                            'title' => '积分记录',
                            'type' => 'button',
                            'route' => 'admin.point_history.list',
                        ],
                        [
                            'id' => '2-20-4',
                            'title' => '添加礼品',
                            'type' => 'button',
                            'route' => 'admin.point_gift.add',
                        ],
                        [
                            'id' => '2-20-5',
                            'title' => '编辑礼品',
                            'type' => 'button',
                            'route' => 'admin.point_gift.edit',
                        ],
                        [
                            'id' => '2-20-6',
                            'title' => '删除礼品',
                            'type' => 'button',
                            'route' => 'admin.point_gift.delete',
                        ],
                    ],
                ],
                [
                    'id' => '2-21',
                    'title' => '会员套餐',
                    'type' => 'button',
                    'children' => [
                        [
                            'id' => '2-21-1',
                            'title' => '套餐列表',
                            'type' => 'button',
                            'route' => 'admin.vip.list',
                        ],
                        [
                            'id' => '2-21-2',
                            'title' => '添加套餐',
                            'type' => 'button',
                            'route' => 'admin.vip.add',
                        ],
                        [
                            'id' => '2-21-3',
                            'title' => '编辑套餐',
                            'type' => 'button',
                            'route' => 'admin.vip.edit',
                        ],
                        [
                            'id' => '2-21-4',
                            'title' => '删除套餐',
                            'type' => 'button',
                            'route' => 'admin.vip.delete',
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
                        ],
                        [
                            'id' => '4-1-5',
                            'title' => '在线记录',
                            'type' => 'button',
                            'route' => 'admin.user.online',
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
                [
                    'id' => '4-4',
                    'title' => '操作记录',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '4-4-1',
                            'title' => '记录列表',
                            'type' => 'menu',
                            'route' => 'admin.audit.list',
                        ],
                        [
                            'id' => '4-4-2',
                            'title' => '搜索记录',
                            'type' => 'menu',
                            'route' => 'admin.audit.search',
                        ],
                        [
                            'id' => '4-4-3',
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
                            'title' => '注册登录',
                            'type' => 'menu',
                            'route' => 'admin.setting.oauth',
                        ],
                        [
                            'id' => '5-1-4',
                            'title' => '邮件设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.mail',
                        ],
                        [
                            'id' => '5-1-5',
                            'title' => '支付设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.pay',
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
                        [
                            'id' => '5-2-6',
                            'title' => '直播设置',
                            'type' => 'menu',
                            'route' => 'admin.setting.live',
                        ],
                    ],
                ],
                [
                    'id' => '5-3',
                    'title' => '营销配置',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '5-3-1',
                            'title' => '积分奖励',
                            'type' => 'menu',
                            'route' => 'admin.setting.point',
                        ],
                    ],
                ],
                [
                    'id' => '5-4',
                    'title' => '其它配置',
                    'type' => 'menu',
                    'children' => [
                        [
                            'id' => '5-4-2',
                            'title' => '微信公众号',
                            'type' => 'menu',
                            'route' => 'admin.setting.wechat_oa',
                        ],
                        [
                            'id' => '5-4-3',
                            'title' => '钉钉机器人',
                            'type' => 'menu',
                            'route' => 'admin.setting.dingtalk_robot',
                        ],
                    ]
                    ,],
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
