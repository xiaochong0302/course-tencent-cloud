<?php

namespace App\Http\Web\Controllers;

/**
 * @RoutePrefix("/im")
 */
class MessengerController extends \Phalcon\Mvc\Controller
{

    /**
     * @Get("/init", name="im.init")
     */
    public function initAction()
    {
        $data = [
            'mine' => [
                'id' => '100000', //我的ID
                'username' => '纸飞机', //我的昵称
                'sign' => '在深邃的编码世界，做一枚轻盈的纸飞机', //我的签名
                'avatar' => '//wx2.sinaimg.cn/mw690/5db11ff4gy1flxmew7edlj203d03wt8n.jpg', //我的头像
                'status' => 'online', //在线状态 online：在线、hide：隐身
            ],
            'friend' => [
                [
                    'id' => '1000',
                    'groupname' => '前端码农',
                    'online' => 3,
                    'list' => [
                        [
                            'id' => '1000',
                            'username' => '闲心',
                            'sign' => '我是如此的不寒而栗',
                            'avatar' => '//wx2.sinaimg.cn/mw690/5db11ff4gy1flxmew7edlj203d03wt8n.jpg', //我的头像
                            'status' => 'online',
                        ],
                        [
                            'id' => '1001',
                            'username' => '妹儿美',
                            'sign' => '我是如此的不寒而栗',
                            'avatar' => '//wx2.sinaimg.cn/mw690/5db11ff4gy1flxmew7edlj203d03wt8n.jpg', //我的头像
                            'status' => 'online',
                        ]
                    ],
                ],
                [
                    'id' => '1001',
                    'groupname' => '后端码农',
                    'online' => 2,
                    'list' => [
                        [
                            'id' => '1003',
                            'username' => '合肥马哥',
                            'sign' => '我是如此的不寒而栗',
                            'avatar' => '//wx2.sinaimg.cn/mw690/5db11ff4gy1flxmew7edlj203d03wt8n.jpg',
                            'status' => 'online',
                        ],
                        [
                            'id' => '1004',
                            'username' => '合肥牛哥',
                            'sign' => '我是如此的不寒而栗',
                            'avatar' => '//wx2.sinaimg.cn/mw690/5db11ff4gy1flxmew7edlj203d03wt8n.jpg',
                            'status' => 'online',
                        ]
                    ],
                ],
                [
                    'id' => '1002',
                    'groupname' => '全栈码农',
                    'online' => 1,
                    'list' => [
                        [
                            'id' => '1005',
                            'username' => '南拳',
                            'sign' => '我是如此的不寒而栗',
                            'avatar' => '//wx2.sinaimg.cn/mw690/5db11ff4gy1flxmew7edlj203d03wt8n.jpg',
                            'status' => 'online',
                        ],
                        [
                            'id' => '1006',
                            'username' => '北腿',
                            'sign' => '我是如此的不寒而栗',
                            'avatar' => '//wx2.sinaimg.cn/mw690/5db11ff4gy1flxmew7edlj203d03wt8n.jpg',
                            'status' => 'online',
                        ]
                    ],
                ],
            ],
            'group' => [
                [
                    'id' => '1001',
                    'groupname' => '前端码农',
                    'avatar' => '//wx2.sinaimg.cn/mw690/5db11ff4gy1flxmew7edlj203d03wt8n.jpg',
                ],
                [
                    'id' => '1002',
                    'groupname' => '后端码农',
                    'avatar' => '//wx2.sinaimg.cn/mw690/5db11ff4gy1flxmew7edlj203d03wt8n.jpg',
                ],
                [
                    'id' => '1003',
                    'groupname' => '全栈码农',
                    'avatar' => '//wx2.sinaimg.cn/mw690/5db11ff4gy1flxmew7edlj203d03wt8n.jpg',
                ],
            ],
        ];

        return $this->jsonSuccess(['data' => $data]);
    }

    /**
     * @Get("/group/members", name="im.group_members")
     */
    public function groupMembersAction()
    {
        $data = [
            'list' => [
                [
                    'id' => '1000',
                    'username' => '闲心',
                    'sign' => '我是如此的不寒而栗',
                    'status' => 'online',
                ],
                [
                    'id' => '1001',
                    'username' => '妹儿美',
                    'sign' => '我是如此的不寒而栗',
                    'status' => 'online',
                ]
            ]
        ];

        return $this->jsonSuccess(['data' => $data]);
    }

    /**
     * @Get("/msg/box", name="im.msg_box")
     */
    public function messageBoxAction()
    {

    }

    /**
     * @Get("/chat/log", name="im.chat_log")
     */
    public function chatLogAction()
    {

    }

    /**
     * @Get("/find", name="im.find")
     */
    public function findAction()
    {

    }

    /**
     * @Post("/user/bind", name="im.bind_user")
     */
    public function bindUserAction()
    {

    }

    /**
     * @Post("/msg/send", name="im.send_msg")
     */
    public function sendMessageAction()
    {

    }

    /**
     * @Post("/img/upload", name="im.upload_img")
     */
    public function uploadImageAction()
    {
    }

    /**
     * @Post("/file/upload", name="im.upload_file")
     */
    public function uploadFileAction()
    {

    }

    /**
     * @Post("/stats/update", name="im.update_stats")
     */
    public function updateStatsAction()
    {

    }

    /**
     * @Post("/sign/update", name="im.update_sign")
     */
    public function updateSignAction()
    {

    }

    /**
     * @Post("/friend/apply", name="im.apply_friend")
     */
    public function applyFriendAction()
    {
    }

    /**
     * @Post("/group/apply", name="im.apply_group")
     */
    public function applyGroupAction()
    {

    }

    /**
     * @Post("/friend/approve", name="im.approve_friend")
     */
    public function approveFriendAction()
    {

    }

    /**
     * @Post("/group/approve", name="im.approve_group")
     */
    public function approveGroupAction()
    {

    }

}
