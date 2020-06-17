<?php

namespace App\Http\Web\Controllers;

use App\Http\Web\Services\Live as LiveService;
use App\Traits\Response as ResponseTrait;

/**
 * @RoutePrefix("/live")
 */
class LiveController extends \Phalcon\Mvc\Controller
{

    use ResponseTrait;

    /**
     * @Get("/{id:[0-9]+}/members", name="web.live.members")
     */
    public function membersAction($id)
    {
        $list = [
            [
                'username' => '直飞机',
                'avatar' => 'http://tp1.sinaimg.cn/5619439268/180/40030060651/1',
                'status' => 'online',
                'sign' => '高舍炮打的准',
                'id' => 1,
            ],
            [
                'username' => '直飞机2',
                'avatar' => 'http://tp1.sinaimg.cn/5619439268/180/40030060651/1',
                'status' => 'online',
                'sign' => '高舍炮打的准',
                'id' => 2,
            ],
            [
                'username' => '直飞机3',
                'avatar' => 'http://tp1.sinaimg.cn/5619439268/180/40030060651/1',
                'status' => 'online',
                'sign' => '高舍炮打的准',
                'id' => 3,
            ],
        ];

        $content = ['data' => ['list' => $list]];

        return $this->jsonSuccess($content);
    }

    /**
     * @Post("/{id:[0-9]+}/bind", name="web.live.bind")
     */
    public function bindAction($id)
    {
        $service = new LiveService();

        $service->bindUser($id);

        return $this->jsonSuccess();
    }

    /**
     * @Post("/{id:[0-9]+}/unbind", name="web.live.unbind")
     */
    public function unbindAction($id)
    {

    }

    /**
     * @Post("/{id:[0-9]+}/message", name="web.live.message")
     */
    public function messageAction($id)
    {
        $service = new LiveService();

        $service->sendMessage($id);

        return $this->jsonSuccess();
    }

}
